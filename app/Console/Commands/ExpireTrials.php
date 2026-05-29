<?php

namespace App\Console\Commands;

use App\Models\OrganizationSubscription;
use App\Models\User;
use App\Notifications\TrialExpiredNotification;
use App\Notifications\TrialExpiringNotification;
use Illuminate\Console\Command;

class ExpireTrials extends Command
{
    protected $signature   = 'subscriptions:expire-trials';
    protected $description = 'Expire overdue trials and send warning emails to org owners.';

    public function handle(): void
    {
        $this->expireOverdueTrials();
        $this->warnExpiringTrials();
    }

    // -------------------------------------------------------------------------
    // Step 1 — Mark expired + notify
    // -------------------------------------------------------------------------

    private function expireOverdueTrials(): void
    {
        $expired = OrganizationSubscription::where('status', OrganizationSubscription::STATUS_TRIALING)
            ->where('trial_ends_at', '<', now())
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No trials to expire.');
            return;
        }

        foreach ($expired as $subscription) {
            $subscription->update(['status' => OrganizationSubscription::STATUS_EXPIRED]);

            $owners = $this->owners($subscription->organization_id);

            foreach ($owners as $owner) {
                $notification = new TrialExpiredNotification();

                // Store in DB notifications table
                $owner->notify($notification);

                // Send via Brevo API
                $notification->sendMail($owner);
            }

            $this->info("Expired trial for org ID: {$subscription->organization_id}");
        }

        $this->info("Expired {$expired->count()} trial(s).");
    }

    // -------------------------------------------------------------------------
    // Step 2 — Warn orgs expiring within 3 days
    // -------------------------------------------------------------------------

    private function warnExpiringTrials(): void
    {
        $expiringSoon = OrganizationSubscription::where('status', OrganizationSubscription::STATUS_TRIALING)
            ->whereBetween('trial_ends_at', [now(), now()->addDays(3)])
            ->get();

        if ($expiringSoon->isEmpty()) {
            $this->info('No trials expiring soon.');
            return;
        }

        foreach ($expiringSoon as $subscription) {
            $owners = $this->owners($subscription->organization_id);

            foreach ($owners as $owner) {
                $notification = new TrialExpiringNotification($subscription->trial_ends_at);

                // Store in DB notifications table
                $owner->notify($notification);

                // Send via Brevo API
                $notification->sendMail($owner);
            }

            $this->info("Warned org ID: {$subscription->organization_id} — trial ends {$subscription->trial_ends_at->toDateString()}");
        }

        $this->info("Warned {$expiringSoon->count()} org(s) of upcoming expiry.");
    }

    // -------------------------------------------------------------------------
    // Shared helper
    // -------------------------------------------------------------------------

    private function owners(int $organizationId)
    {
        return User::where('organization_id', $organizationId)
            ->where('role', 'owner')
            ->get();
    }
}