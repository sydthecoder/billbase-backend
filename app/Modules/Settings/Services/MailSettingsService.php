<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationMailSetting;
use App\Services\OrganizationSettingsResolver;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MailSettingsService
{
    public function get(User $user): JsonResponse
    {
        $setting = OrganizationSettingsResolver::for($user->organization_id)->mailSetting();

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

        // Preserve masked secrets — don't overwrite stored secret with placeholder
        if ($data['driver'] === 'smtp') {
            $password = $config['password'] ?? null;

            if (($password === null || $password === '' || $password === '••••••••') && $existing?->driver === 'smtp') {
                $config['password'] = $existing->config['password'] ?? '';
            }
        }

        if ($data['driver'] === 'brevo') {
            $apiKey = $config['api_key'] ?? null;

            if (($apiKey === null || $apiKey === '' || $apiKey === '••••••••') && $existing?->driver === 'brevo') {
                $config['api_key'] = $existing->config['api_key'] ?? '';
            }
        }

        $setting = OrganizationMailSetting::updateOrCreate(
            ['organization_id' => $user->organization_id],
            [
                'driver'      => $data['driver'],
                'from_name'   => $data['from_name'],
                'from_email'  => $data['from_email'],
                'config'      => $config,
                'is_verified' => false, // always reset on save — must re-test
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
            // Build a scoped mailer from the resolver — no global config() mutation
            $resolver = OrganizationSettingsResolver::for($user->organization_id);
            $mailer   = $resolver->buildMailer(requireVerified: false);

            if (! $mailer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Could not build mailer from current settings. Check driver configuration.',
                ], 422);
            }

            $from = $resolver->mailFrom();

            // Send via the scoped mailer directly — no Laravel Mail facade, no global state
            $email = (new Email())
                ->from(new Address($from['address'], $from['name']))
                ->to($recipient)
                ->subject('PayFlow — Mail Settings Test')
                ->text('This is a test email from PayFlow. Your mail settings are working correctly.');

            $mailer->getSymfonyTransport()->send($email);

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

        // Never expose secrets in responses
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