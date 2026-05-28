<?php

namespace App\Services;

use App\Models\OrganizationBankAccount;
use App\Models\OrganizationMailSetting;
use App\Models\OrganizationPreference;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Transport\LogTransport;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Crypto\DkimSigner;

/**
 * OrganizationSettingsResolver
 *
 * Single boundary for reading any org setting.
 * All modules call this instead of touching OrganizationPreference / config() directly.
 *
 * Usage:
 *   $resolver = new OrganizationSettingsResolver($organizationId);
 *   $resolver->get('invoice_prefix');        // preference key
 *   $resolver->tax('rate');                  // tax config
 *   $resolver->mailSetting();                // full mail row or null
 *   $resolver->buildMailer();                // scoped Mailer instance (no global config mutation)
 *   $resolver->bankAccount();                // formatted bank account or null
 *
 * Caching:
 *   DB rows are loaded once per resolver instance (per request).
 *   Construct a new instance per organization if you need fresh data.
 */
class OrganizationSettingsResolver
{
    private int $organizationId;

    // Lazy-loaded, cached per instance
    private ?OrganizationPreference $prefsRow   = null;
    private ?OrganizationMailSetting $mailRow    = null;
    private ?OrganizationBankAccount $bankRow    = null;

    private bool $prefsLoaded = false;
    private bool $mailLoaded  = false;
    private bool $bankLoaded  = false;

    /** System-wide preference defaults from config/settings.php */
    private array $preferenceDefaults;

    public function __construct(int $organizationId)
    {
        $this->organizationId     = $organizationId;
        $this->preferenceDefaults = config('settings.organization_preferences', []);
    }

    // -------------------------------------------------------------------------
    // Factory
    // -------------------------------------------------------------------------

    public static function for(int $organizationId): static
    {
        return new static($organizationId);
    }

    // -------------------------------------------------------------------------
    // Preferences
    // -------------------------------------------------------------------------

    /**
     * Get a single preference key.
     * DB value wins; falls back to config default; returns $fallback if key unknown.
     */
    public function get(string $key, mixed $fallback = null): mixed
    {
        $prefs = $this->loadPrefs();

        $dbValue = $prefs?->$key ?? null;

        if ($dbValue !== null) {
            return $dbValue;
        }

        return $this->preferenceDefaults[$key] ?? $fallback;
    }

    /**
     * Return all resolved preferences (DB overrides merged over config defaults).
     * This is what the Settings API endpoint should return — all keys, nothing missing.
     */
    public function allPreferences(): array
    {
        $prefs = $this->loadPrefs();

        return [
            // Invoice
            'invoice_prefix'          => $prefs?->invoice_prefix          ?? $this->preferenceDefaults['invoice_prefix'],
            'invoice_starting_number' => $prefs?->invoice_starting_number ?? $this->preferenceDefaults['invoice_starting_number'],
            'default_payment_terms'   => $prefs?->default_payment_terms   ?? $this->preferenceDefaults['default_payment_terms'],
            'invoice_footer'          => $prefs?->invoice_footer           ?? $this->preferenceDefaults['invoice_footer'],
            'invoice_notes'           => $prefs?->invoice_notes            ?? $this->preferenceDefaults['invoice_notes'],
            'invoice_template'        => $prefs?->invoice_template         ?? $this->preferenceDefaults['invoice_template'],   // was missing before

            // Quote
            'quote_prefix'            => $prefs?->quote_prefix             ?? $this->preferenceDefaults['quote_prefix'],
            'quote_starting_number'   => $prefs?->quote_starting_number    ?? $this->preferenceDefaults['quote_starting_number'],
            'quote_template'          => $prefs?->quote_template           ?? $this->preferenceDefaults['quote_template'],     // was missing before

            // Customer
            'customer_code_prefix'    => $prefs?->customer_code_prefix     ?? $this->preferenceDefaults['customer_code_prefix'],

            // Branding
            'brand_color'             => $prefs?->brand_color              ?? $this->preferenceDefaults['brand_color'],
        ];
    }

    // -------------------------------------------------------------------------
    // Tax (config only — SA VAT is fixed, no per-org override)
    // -------------------------------------------------------------------------

    /**
     * Get a tax config value.
     *
     * Example: $resolver->tax('rate')  // 15.00
     *          $resolver->tax('label') // 'VAT'
     */
    public function tax(string $key, mixed $fallback = null): mixed
    {
        return config("settings.tax.{$key}", $fallback);
    }

    // -------------------------------------------------------------------------
    // Mail settings
    // -------------------------------------------------------------------------

    /**
     * Return the raw mail setting row, or null if none configured.
     */
    public function mailSetting(): ?OrganizationMailSetting
    {
        return $this->loadMail();
    }

    /**
     * Build a scoped Mailer instance from this org's mail settings.
     *
     * This replaces the old config() mutation pattern.
     * The returned Mailer is isolated to this call — no global state is touched.
     *
     * Returns null if the org has no verified mail settings configured.
     * Pass $requireVerified = false to build even from unverified settings (e.g. for test sends).
     */
    public function buildMailer(bool $requireVerified = true): ?Mailer
    {
        $setting = $this->loadMail();

        if (! $setting) {
            return null;
        }

        if ($requireVerified && ! $setting->is_verified) {
            return null;
        }

        $config = $setting->config;

        try {
            if ($setting->driver === 'smtp') {
                $transport = $this->buildSmtpTransport($config);
            } else {
                // Future drivers (brevo, mailgun, etc.) go here
                Log::warning("OrganizationSettingsResolver: unsupported mail driver [{$setting->driver}] for org {$this->organizationId}");
                return null;
            }

            $symfonyMailer = new \Symfony\Component\Mailer\Mailer($transport);

            return new Mailer(
                name: 'tenant',
                views: app('view'),
                mailer: $symfonyMailer,
                events: app('events'),
            );

        } catch (\Throwable $e) {
            Log::error("OrganizationSettingsResolver: failed to build mailer for org {$this->organizationId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the from address/name for this org's mail settings.
     * Returns ['address' => ..., 'name' => ...] or null.
     */
    public function mailFrom(): ?array
    {
        $setting = $this->loadMail();

        if (! $setting) {
            return null;
        }

        return [
            'address' => $setting->from_email,
            'name'    => $setting->from_name,
        ];
    }

    // -------------------------------------------------------------------------
    // Bank account
    // -------------------------------------------------------------------------

    /**
     * Return formatted bank account data (with human-readable labels), or null.
     */
    public function bankAccount(): ?array
    {
        $account = $this->loadBank();

        if (! $account) {
            return null;
        }

        return [
            'bank_name'           => $account->bank_name,
            'bank_label'          => config('lookup.south_african_banks')[$account->bank_name] ?? $account->bank_name,
            'account_holder'      => $account->account_holder,
            'account_number'      => $account->account_number,
            'branch_code'         => $account->branch_code,
            'account_type'        => $account->account_type,
            'account_type_label'  => config('lookup.account_types')[$account->account_type] ?? $account->account_type,
            'is_active'           => $account->is_active,
        ];
    }

    // -------------------------------------------------------------------------
    // Internal loaders (lazy, cached per instance)
    // -------------------------------------------------------------------------

    private function loadPrefs(): ?OrganizationPreference
    {
        if (! $this->prefsLoaded) {
            $this->prefsRow   = OrganizationPreference::where('organization_id', $this->organizationId)->first();
            $this->prefsLoaded = true;
        }

        return $this->prefsRow;
    }

    private function loadMail(): ?OrganizationMailSetting
    {
        if (! $this->mailLoaded) {
            $this->mailRow   = OrganizationMailSetting::where('organization_id', $this->organizationId)->first();
            $this->mailLoaded = true;
        }

        return $this->mailRow;
    }

    private function loadBank(): ?OrganizationBankAccount
    {
        if (! $this->bankLoaded) {
            $this->bankRow   = OrganizationBankAccount::where('organization_id', $this->organizationId)->first();
            $this->bankLoaded = true;
        }

        return $this->bankRow;
    }

    // -------------------------------------------------------------------------
    // Transport builders (private)
    // -------------------------------------------------------------------------

    private function buildSmtpTransport(array $config): EsmtpTransport
    {
        $tls  = ($config['encryption'] ?? 'tls') === 'ssl';
        $port = (int) ($config['port'] ?? 587);

        $transport = new EsmtpTransport(
            host: $config['host'],
            port: $port,
            tls:  $tls,
        );

        if (! empty($config['username'])) {
            $transport->setUsername($config['username']);
        }

        if (! empty($config['password'])) {
            $transport->setPassword($config['password']);
        }

        return $transport;
    }
}