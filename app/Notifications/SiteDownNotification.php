<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SiteDownNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Site $site,
        public ?string $errorMessage = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("🚨 Alert: {$this->site->name} is DOWN")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your monitored site **{$this->site->name}** is currently **DOWN**.")
            ->line("**URL:** {$this->site->url}")
            ->line("**Status:** Failed");

        if ($this->errorMessage) {
            $mail->line("**Error:** {$this->errorMessage}");
        }

        return $mail
            ->line("**Time:** " . now()->format('Y-m-d H:i:s'))
            ->action('View Dashboard', route('sites.dashboard'))
            ->line('Thank you for using Uptime Monitoring!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'site_id' => $this->site->id,
            'site_name' => $this->site->name,
            'site_url' => $this->site->url,
            'status' => 'down',
            'error_message' => $this->errorMessage,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
