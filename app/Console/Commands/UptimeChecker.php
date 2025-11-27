<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\SiteLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class UptimeChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uptime:check {--site-id= : Check specific site by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the uptime status of all monitored sites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting uptime check...');

        $query = Site::where('is_active', true);

        // If site-id option is provided, check only that site
        if ($this->option('site-id')) {
            $query->where('id', $this->option('site-id'));
        } else {
            // Only check sites that need to be checked based on their interval
            $query->where(function ($q) {
                $q->whereNull('last_checked_at')
                  ->orWhereRaw('TIMESTAMPDIFF(MINUTE, last_checked_at, NOW()) >= check_interval');
            });
        }

        $sites = $query->get();

        if ($sites->isEmpty()) {
            $this->info('No sites to check at this time.');
            return;
        }

        $this->info("Checking {$sites->count()} site(s)...");

        $progressBar = $this->output->createProgressBar($sites->count());
        $progressBar->start();

        foreach ($sites as $site) {
            $this->checkSite($site);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info('Uptime check completed!');
    }

    /**
     * Check the uptime status of a single site
     */
    protected function checkSite(Site $site): void
    {
        $startTime = microtime(true);
        $isUp = false;
        $statusCode = null;
        $errorMessage = null;
        $responseTime = null;

        try {
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false, // For testing, consider enabling SSL verification in production
                    'allow_redirects' => true,
                ])
                ->get($site->url);

            $endTime = microtime(true);
            $responseTime = (int) (($endTime - $startTime) * 1000); // Convert to milliseconds
            $statusCode = $response->status();
            
            // Consider 2xx and 3xx status codes as "up"
            $isUp = $response->successful() || $response->redirect();

            if (!$isUp) {
                $errorMessage = "HTTP {$statusCode}";
            }

        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTime = (int) (($endTime - $startTime) * 1000);
            $isUp = false;
            $errorMessage = $e->getMessage();
        }

        // Create log entry
        SiteLog::create([
            'site_id' => $site->id,
            'is_up' => $isUp,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => Carbon::now(),
        ]);

        // Update site status
        $site->update([
            'is_up' => $isUp,
            'last_checked_at' => Carbon::now(),
            'last_response_time' => $responseTime,
            'last_status_code' => $statusCode,
        ]);

        // Log to console
        $status = $isUp ? '<info>UP</info>' : '<error>DOWN</error>';
        $this->newLine();
        $this->line("  {$site->name}: {$status} ({$responseTime}ms)");
    }
}
