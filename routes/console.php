<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

// Schedule uptime checks every minute
Schedule::command('uptime:check')->everyMinute();

// Prune site logs older than 7 days every Sunday at 3 AM
Schedule::command('logs:prune --days=7')->weeklyOn(0, '03:00');
