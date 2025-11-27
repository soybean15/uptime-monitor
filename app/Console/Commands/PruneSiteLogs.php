<?php

namespace App\Console\Commands;

use App\Models\SiteLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneSiteLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:prune {--days=30 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete site logs older than a specified number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Pruning site logs older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})...");

        $deletedCount = SiteLog::where('checked_at', '<', $cutoffDate)->delete();

        if ($deletedCount > 0) {
            $this->info("✓ Successfully deleted {$deletedCount} old log entries.");
        } else {
            $this->info('No logs to prune.');
        }

        return Command::SUCCESS;
    }
}
