<?php

namespace App\Notifications;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrialExpiredNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function sendMail(object $notifiable): void
    {
        MailService::send(
            to:      $notifiable->email,
            name:    $notifiable->first_name,
            subject: 'Your Bill Base trial has ended',
            view:    'emails.system.trials.expired',
            data:    [
                'user' => $notifiable,
            ],
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'trial_expired',
            'message'    => 'Your free trial has ended. Upgrade to restore full access.',
            'action_url' => url('/settings/billing'),
        ];
    }
}