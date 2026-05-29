<?php

namespace App\Notifications;

use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrialExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(private Carbon $trialEndsAt) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function sendMail(object $notifiable): void
    {
        $daysLeft = (int) now()->diffInDays($this->trialEndsAt);

        MailService::send(
            to:      $notifiable->email,
            name:    $notifiable->first_name,
            subject: "Your Bill Base trial ends in {$daysLeft} " . str('day')->plural($daysLeft),
            view:    'emails.system.trials.expiring',
            data:    [
                'user'        => $notifiable,
                'trialEndsAt' => $this->trialEndsAt,
                'daysLeft'    => $daysLeft,
            ],
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'          => 'trial_expiring',
            'message'       => "Your trial ends on {$this->trialEndsAt->format('d M Y')}. Upgrade to keep access.",
            'trial_ends_at' => $this->trialEndsAt->toDateTimeString(),
            'action_url'    => url('/settings/billing'),
        ];
    }
}