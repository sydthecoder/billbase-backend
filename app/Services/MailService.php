<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MailService — Central mail sender for all SYSTEM emails (Bill Base → org owner).
 *
 * Uses Brevo API directly. Renders blade views for clean, maintainable templates.
 * All system email views live under resources/views/emails/system/
 *
 * Usage:
 *   // With a blade view (preferred)
 *   MailService::send(
 *       to:      'owner@example.com',
 *       name:    'John',
 *       subject: 'Your trial is ending',
 *       view:    'emails.system.trials.expiring',
 *       data:    ['user' => $user, 'trialEndsAt' => $date, 'daysLeft' => 3],
 *   );
 *
 *   // With raw HTML (fallback, avoid where possible)
 *   MailService::send(
 *       to:      'owner@example.com',
 *       name:    'John',
 *       subject: 'Hello',
 *       html:    '<p>Hi John</p>',
 *   );
 *
 * Returns true on success, false on failure (logs the error).
 *
 * NOTE: This service is for system emails only (Bill Base → owner).
 *       For tenant emails to their customers, use CustomerMailService.
 */
class MailService
{
    /**
     * Send a system email via Brevo.
     *
     * @param  string       $to       Recipient email address
     * @param  string       $name     Recipient display name
     * @param  string       $subject  Email subject line
     * @param  string|null  $view     Blade view path e.g. 'emails.system.trials.expiring'
     * @param  array        $data     Data passed to the blade view
     * @param  string|null  $html     Raw HTML fallback (used if no view provided)
     * @param  string|null  $replyTo  Optional reply-to address
     */
    public static function send(
        string  $to,
        string  $name,
        string  $subject,
        ?string $view    = null,
        array   $data    = [],
        ?string $html    = null,
        ?string $replyTo = null,
    ): bool {
        // Render blade view if provided, otherwise fall back to raw html
        $htmlContent = $view
            ? view($view, array_merge($data, ['subject' => $subject]))->render()
            : $html;

        $payload = [
            'sender' => [
                'email' => config('services.brevo.sender_email'),
                'name'  => config('services.brevo.sender_name'),
            ],
            'to' => [
                ['email' => $to, 'name' => $name],
            ],
            'subject'     => $subject,
            'htmlContent' => $htmlContent,
        ];

        if ($replyTo) {
            $payload['replyTo'] = ['email' => $replyTo];
        }

        try {
            $response = Http::withHeaders([
                'api-key'      => config('services.brevo.key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.brevo.endpoint'), $payload);

            if ($response->successful()) {
                Log::info("MailService: sent [{$subject}] to [{$to}]");
                return true;
            }

            Log::error("MailService: Brevo rejected email to [{$to}]", [
                'subject'  => $subject,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            return false;

        } catch (\Throwable $e) {
            Log::error("MailService: exception sending to [{$to}]", [
                'subject' => $subject,
                'error'   => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send the same system email to multiple recipients.
     *
     * @param  array<array{email: string, name: string}>  $recipients
     */
    public static function sendToMany(
        array   $recipients,
        string  $subject,
        ?string $view    = null,
        array   $data    = [],
        ?string $html    = null,
        ?string $replyTo = null,
    ): void {
        foreach ($recipients as $recipient) {
            static::send(
                to:      $recipient['email'],
                name:    $recipient['name'],
                subject: $subject,
                view:    $view,
                data:    $data,
                html:    $html,
                replyTo: $replyTo,
            );
        }
    }
}