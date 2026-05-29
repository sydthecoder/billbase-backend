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
    protected $description = 'Expire overdue trials and send expiry warning emails to org owners.';

    public function handle(): void
    {
        $this->expireOverdueTrials();
        $this->warnExpiringTrials();
    }

    // -------------------------------------------------------------------------
    // Step 1 — Mark expired
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

            // Notify all owners of this org
            $this->notifyOwners($subscription->organization_id, 'expired');

            $this->info("Expired trial for org ID: {$subscription->organization_id}");
        }

        $this->info("Expired {$expired->count()} trial(s).");
    }

    // -------------------------------------------------------------------------
    // Step 2 — Warn orgs expiring in 3 days
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
            $this->notifyOwners($subscription->organization_id, 'expiring', $subscription);

            $this->info("Warned org ID: {$subscription->organization_id} — trial ends {$subscription->trial_ends_at->toDateString()}");
        }

        $this->info("Warned {$expiringSoon->count()} org(s) of upcoming expiry.");
    }

    // -------------------------------------------------------------------------
    // Shared — notify all owners of an org
    // -------------------------------------------------------------------------

    private function notifyOwners(int $organizationId, string $type, ?OrganizationSubscription $subscription = null): void
    {
        $owners = User::where('organization_id', $organizationId)
            ->where('role', 'owner')
            ->get();

        foreach ($owners as $owner) {
            if ($type === 'expired') {
                $owner->notify(new TrialExpiredNotification());
            }

            if ($type === 'expiring' && $subscription) {
                $owner->notify(new TrialExpiringNotification($subscription->trial_ends_at));
            }
        }
    }
}