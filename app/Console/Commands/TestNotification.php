<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\User;
use App\Notifications\SiteDownNotification;
use App\Notifications\SiteUpNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:test {--type=down : Type of notification (down or up)} {--user-id= : Specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test send notification to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $userId = $this->option('user-id');

        // Get site
        $site = Site::first();
        // dd($site);
        if (!$site) {
            $this->error('No sites found. Please create a site first.');
            return Command::FAILURE;
        }

        // Get user(s)
        if ($userId) {
            $users = User::where('id', $userId)->get();
            // dd($users   );
        } else {
            $users = User::all();
            // dd($users);
        }

        if ($users->isEmpty()) {
            $this->error('No users found.');
            return Command::FAILURE;
        }

        $this->info("Sending {$type} notification to {$users->count()} user(s)...");

        if ($type === 'down') {
            Notification::send($users, new SiteDownNotification($site, 'Test: Connection timeout'));
            $this->info('✓ Site DOWN notifications sent!');
        } else {
            Notification::send($users, new SiteUpNotification($site, 15));
            $this->info('✓ Site UP notifications sent!');
        }

        $this->info('Check your email inbox in a few seconds...');
        return Command::SUCCESS;
    }
}
