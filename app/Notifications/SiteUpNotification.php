<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SiteUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Site $site,
        public int $downtime = 0,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("✅ Site Recovered: {$this->site->name} is BACK UP")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your monitored site **{$this->site->name}** is now **UP** and responding normally.")
            ->line("**URL:** {$this->site->url}")
            ->line("**Status:** OK")
            ->line("**Recovery Time:** " . now()->format('Y-m-d H:i:s'))
            ->action('View Dashboard', route('sites.dashboard'))
            ->line('Thank you for using Uptime Monitoring!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'site_id' => $this->site->id,
            'site_name' => $this->site->name,
            'site_url' => $this->site->url,
            'status' => 'up',
            'downtime_minutes' => $this->downtime,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
