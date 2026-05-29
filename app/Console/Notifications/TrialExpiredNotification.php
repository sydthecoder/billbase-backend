<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TrialExpiredNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        // TODO: switch to 'mail' once SMTP is configured
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // TODO: wire up real mail service here
        // This will fire automatically once 'mail' is added to via() above
        return (new MailMessage)
            ->subject('Your Bill Base trial has ended')
            ->greeting("Hi {$notifiable->first_name},")
            ->line('Your 14-day free trial has ended and your account is now restricted.')
            ->line('Upgrade to a paid plan to restore full access to your invoices, quotes, and customer data.')
            ->action('Choose a Plan', url('/settings/billing'))
            ->line('Your data is safe — it will be here when you are ready.');
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