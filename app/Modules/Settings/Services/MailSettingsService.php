<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationMailSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class MailSettingsService
{
    public function get(User $user): JsonResponse
    {
        $setting = OrganizationMailSetting::where('organization_id', $user->organization_id)->first();

        if (! $setting) {
            return response()->json([
                'status' => 'success',
                'data'   => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->formatSetting($setting),
        ]);
    }

    public function save(User $user, array $data): JsonResponse
    {
        $existing = OrganizationMailSetting::where('organization_id', $user->organization_id)->first();

        $config = $data['config'];

        if ($data['driver'] === 'smtp') {
            $password = $config['password'] ?? null;

            if (($password === null || $password === '' || $password === '••••••••') && $existing && $existing->driver === 'smtp') {
                $config['password'] = $existing->config['password'] ?? '';
            }
        }

        if ($data['driver'] === 'brevo') {
            $apiKey = $config['api_key'] ?? null;

            if (($apiKey === null || $apiKey === '' || $apiKey === '••••••••') && $existing && $existing->driver === 'brevo') {
                $config['api_key'] = $existing->config['api_key'] ?? '';
            }
        }

        // updateOrCreate — one row per org, replace not duplicate
        $setting = OrganizationMailSetting::updateOrCreate(
            ['organization_id' => $user->organization_id],
            [
                'driver'      => $data['driver'],
                'from_name'   => $data['from_name'],
                'from_email'  => $data['from_email'],
                'config'      => $config,
                'is_verified' => false, // reset verification on every save
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Mail settings saved. Send a test email to verify.',
            'data'    => $this->formatSetting($setting),
        ]);
    }

    public function test(User $user, string $recipient): JsonResponse
    {
        $setting = OrganizationMailSetting::where('organization_id', $user->organization_id)->first();

        if (! $setting) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No mail settings configured yet.',
            ], 422);
        }

        try {
            $config = $setting->config;

            if ($setting->driver === 'smtp') {
                // Temporarily swap Laravel mailer config for this request
                config([
                    'mail.mailers.smtp.host'       => $config['host'],
                    'mail.mailers.smtp.port'        => $config['port'],
                    'mail.mailers.smtp.encryption'  => $config['encryption'],
                    'mail.mailers.smtp.username'    => $config['username'],
                    'mail.mailers.smtp.password'    => $config['password'],
                    'mail.from.address'             => $setting->from_email,
                    'mail.from.name'                => $setting->from_name,
                ]);
            }

            // Future: handle brevo driver here
            // if ($setting->driver === 'brevo') { ... }

            Mail::raw('This is a test email from PayFlow. Your mail settings are working correctly.', function (Message $message) use ($recipient, $setting) {
                $message->to($recipient)
                        ->subject('PayFlow — Mail Settings Test')
                        ->from($setting->from_email, $setting->from_name);
            });

            // Mark as verified
            $setting->update(['is_verified' => true]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Test email sent successfully. Mail settings verified.',
            ]);

        } catch (\Throwable $e) {
            $setting->update(['is_verified' => false]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function formatSetting(OrganizationMailSetting $setting): array
    {
        $config = $setting->config;

        // Never expose password or api_key in response
        if (isset($config['password'])) {
            $config['password'] = '••••••••';
        }

        if (isset($config['api_key'])) {
            $config['api_key'] = '••••••••';
        }

        return [
            'driver'      => $setting->driver,
            'from_name'   => $setting->from_name,
            'from_email'  => $setting->from_email,
            'config'      => $config,
            'is_verified' => $setting->is_verified,
        ];
    }
}