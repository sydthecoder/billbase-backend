<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TrialExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(private Carbon $trialEndsAt) {}

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
            ->subject('Your Bill Base trial ends in 3 days')
            ->greeting("Hi {$notifiable->first_name},")
            ->line("Your free trial ends on {$this->trialEndsAt->format('d M Y')}.")
            ->line('Upgrade now to keep access to all your invoices, quotes, and customer data.')
            ->action('Upgrade My Plan', url('/settings/billing'))
            ->line('Questions? Reply to this email — we are happy to help.');
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