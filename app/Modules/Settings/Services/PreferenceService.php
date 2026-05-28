<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationPreference;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PreferenceService
{
    // System defaults from config/settings.php
    private array $defaults;

    public function __construct()
    {
        $this->defaults = config('settings.organization_preferences');
    }

    public function get(User $user): JsonResponse
    {
        $prefs = OrganizationPreference::where('organization_id', $user->organization_id)->first();

        return response()->json([
            'status' => 'success',
            'data'   => $this->resolvePreferences($prefs),
        ]);
    }

    public function update(User $user, array $data): JsonResponse
    {
        $prefs = OrganizationPreference::updateOrCreate(
            ['organization_id' => $user->organization_id],
            $data
        );

        return response()->json([
            'status' => 'success',
            'data'   => $this->resolvePreferences($prefs->fresh()),
        ]);
    }

    /**
     * Merge tenant preferences over system defaults.
     * Tenant value wins if set, system default used if null.
     */
    private function resolvePreferences(?OrganizationPreference $prefs): array
    {
        $d = $this->defaults;

        return [
            'invoice_prefix'          => $prefs?->invoice_prefix          ?? $d['invoice_prefix'],
            'invoice_starting_number' => $prefs?->invoice_starting_number ?? $d['invoice_starting_number'],
            'default_payment_terms'   => $prefs?->default_payment_terms   ?? $d['default_payment_terms'],
            'invoice_footer'          => $prefs?->invoice_footer           ?? $d['invoice_footer'],
            'invoice_notes'           => $prefs?->invoice_notes            ?? $d['invoice_notes'],
            'quote_prefix'            => $prefs?->quote_prefix             ?? $d['quote_prefix'],
            'quote_starting_number'   => $prefs?->quote_starting_number   ?? $d['quote_starting_number'],
            'customer_code_prefix'    => $prefs?->customer_code_prefix     ?? $d['customer_code_prefix'],
            'brand_color'             => $prefs?->brand_color              ?? $d['brand_color'],
        ];
    }
}