<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\SiteLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UptimeMonitoringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_site()
    {
        $site = Site::create([
            'name' => 'Test Site',
            'url' => 'https://example.com',
            'check_interval' => 5,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('sites', [
            'name' => 'Test Site',
            'url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function it_can_log_site_checks()
    {
        $site = Site::create([
            'name' => 'Test Site',
            'url' => 'https://example.com',
            'check_interval' => 5,
            'is_active' => true,
        ]);

        $log = SiteLog::create([
            'site_id' => $site->id,
            'is_up' => true,
            'response_time' => 150,
            'status_code' => 200,
            'checked_at' => now(),
        ]);

        $this->assertDatabaseHas('site_logs', [
            'site_id' => $site->id,
            'is_up' => true,
            'status_code' => 200,
        ]);
    }

    /** @test */
    public function it_calculates_uptime_percentage_correctly()
    {
        $site = Site::create([
            'name' => 'Test Site',
            'url' => 'https://example.com',
            'check_interval' => 5,
            'is_active' => true,
        ]);

        // Create 8 successful checks
        for ($i = 0; $i < 8; $i++) {
            SiteLog::create([
                'site_id' => $site->id,
                'is_up' => true,
                'response_time' => 150,
                'status_code' => 200,
                'checked_at' => now()->subMinutes($i * 5),
            ]);
        }

        // Create 2 failed checks
        for ($i = 0; $i < 2; $i++) {
            SiteLog::create([
                'site_id' => $site->id,
                'is_up' => false,
                'response_time' => 0,
                'status_code' => 500,
                'checked_at' => now()->subMinutes(($i + 8) * 5),
            ]);
        }

        // 8 out of 10 = 80%
        $this->assertEquals(80.0, $site->uptimePercentage);
    }

    /** @test */
    public function it_has_relationship_between_site_and_logs()
    {
        $site = Site::create([
            'name' => 'Test Site',
            'url' => 'https://example.com',
            'check_interval' => 5,
            'is_active' => true,
        ]);

        SiteLog::create([
            'site_id' => $site->id,
            'is_up' => true,
            'response_time' => 150,
            'status_code' => 200,
            'checked_at' => now(),
        ]);

        $this->assertCount(1, $site->logs);
        $this->assertEquals($site->id, $site->logs->first()->site_id);
    }

    /** @test */
    public function uptime_check_command_exists()
    {
        $this->artisan('uptime:check')
            ->expectsOutput('Starting uptime check...')
            ->assertExitCode(0);
    }
}
