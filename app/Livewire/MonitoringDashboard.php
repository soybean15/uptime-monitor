<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Site;
use App\Models\SiteLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringDashboard extends Component
{
    public $timeRange = '24h'; // 24h, 7d, 30d, 90d

    public function render()
    {
        $analytics = $this->getAnalytics();
        $sites = Site::with('latestLog')->get();
        $recentLogs = SiteLog::with('site')
            ->orderBy('checked_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.monitoring-dashboard', [
            'analytics' => $analytics,
            'sites' => $sites,
            'recentLogs' => $recentLogs,
        ]);
    }

    public function updatedTimeRange()
    {
        // Trigger re-render when time range changes
    }

    protected function getAnalytics(): array
    {
        $startDate = $this->getStartDate();

        return [
            'totalSites' => $this->getTotalSites(),
            'activeSites' => $this->getActiveSites(),
            'sitesUp' => $this->getSitesUp(),
            'sitesDown' => $this->getSitesDown(),
            'overallUptime' => $this->getOverallUptime($startDate),
            'averageResponseTime' => $this->getAverageResponseTime($startDate),
            'totalChecks' => $this->getTotalChecks($startDate),
            'failedChecks' => $this->getFailedChecks($startDate),
            'chartData' => $this->getChartData($startDate),
            'responseTimeChart' => $this->getResponseTimeChart($startDate),
            'topSlowestSites' => $this->getTopSlowestSites($startDate),
            'mostUnreliableSites' => $this->getMostUnreliableSites($startDate),
        ];
    }

    protected function getStartDate(): Carbon
    {
        return match($this->timeRange) {
            '24h' => Carbon::now()->subDay(),
            '7d' => Carbon::now()->subDays(7),
            '30d' => Carbon::now()->subDays(30),
            '90d' => Carbon::now()->subDays(90),
            default => Carbon::now()->subDay(),
        };
    }

    protected function getTotalSites(): int
    {
        return Site::count();
    }

    protected function getActiveSites(): int
    {
        return Site::where('is_active', true)->count();
    }

    protected function getSitesUp(): int
    {
        return Site::where('is_active', true)
            ->where('is_up', true)
            ->count();
    }

    protected function getSitesDown(): int
    {
        return Site::where('is_active', true)
            ->where('is_up', false)
            ->count();
    }

    protected function getOverallUptime(Carbon $startDate): float
    {
        $totalLogs = SiteLog::where('checked_at', '>=', $startDate)->count();
        
        if ($totalLogs === 0) {
            return 100.0;
        }

        $upLogs = SiteLog::where('checked_at', '>=', $startDate)
            ->where('is_up', true)
            ->count();

        return round(($upLogs / $totalLogs) * 100, 2);
    }

    protected function getAverageResponseTime(Carbon $startDate): ?int
    {
        $avg = SiteLog::where('checked_at', '>=', $startDate)
            ->where('is_up', true)
            ->whereNotNull('response_time')
            ->avg('response_time');
        
        return $avg ? (int) round($avg) : null;
    }

    protected function getTotalChecks(Carbon $startDate): int
    {
        return SiteLog::where('checked_at', '>=', $startDate)->count();
    }

    protected function getFailedChecks(Carbon $startDate): int
    {
        return SiteLog::where('checked_at', '>=', $startDate)
            ->where('is_up', false)
            ->count();
    }

    protected function getChartData(Carbon $startDate): array
    {
        $groupBy = $this->getGroupByFormat();
        
        $data = SiteLog::where('checked_at', '>=', $startDate)
            ->select(
                DB::raw($groupBy['select'] . ' as period'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN is_up = 1 THEN 1 ELSE 0 END) as up_count'),
                DB::raw('SUM(CASE WHEN is_up = 0 THEN 1 ELSE 0 END) as down_count')
            )
            ->groupByRaw($groupBy['select'])
            ->orderBy('period')
            ->get();

        return [
            'labels' => $data->pluck('period')->map(fn($p) => (string)$p)->toArray(),
            'uptime' => $data->map(function($item) {
                $total = (int) $item->total;
                return $total > 0 ? round(((int)$item->up_count / $total) * 100, 2) : 100;
            })->toArray(),
            'checks' => $data->pluck('total')->map(fn($v) => (int)$v)->toArray(),
        ];
    }

    protected function getResponseTimeChart(Carbon $startDate): array
    {
        $groupBy = $this->getGroupByFormat();
        
        $data = SiteLog::where('checked_at', '>=', $startDate)
            ->where('is_up', true)
            ->whereNotNull('response_time')
            ->select(
                DB::raw($groupBy['select'] . ' as period'),
                DB::raw('AVG(response_time) as avg_response'),
                DB::raw('MIN(response_time) as min_response'),
                DB::raw('MAX(response_time) as max_response')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $data->pluck('period')->toArray(),
            'average' => $data->map(function($item) {
                return $item->avg_response ? round($item->avg_response, 0) : 0;
            })->toArray(),
            'min' => $data->pluck('min_response')->toArray(),
            'max' => $data->pluck('max_response')->toArray(),
        ];
    }

    protected function getGroupByFormat(): array
    {
        return match($this->timeRange) {
            '24h' => [
                'select' => "DATE_FORMAT(checked_at, '%H:00')",
                'format' => 'H:i',
            ],
            '7d' => [
                'select' => "DATE_FORMAT(checked_at, '%Y-%m-%d')",
                'format' => 'M d',
            ],
            '30d' => [
                'select' => "DATE_FORMAT(checked_at, '%Y-%m-%d')",
                'format' => 'M d',
            ],
            '90d' => [
                'select' => "DATE_FORMAT(checked_at, '%Y-%m-%d')",
                'format' => 'M d',
            ],
            default => [
                'select' => "DATE_FORMAT(checked_at, '%H:00')",
                'format' => 'H:i',
            ],
        };
    }

    protected function getTopSlowestSites(Carbon $startDate, int $limit = 5): array
    {
        return Site::whereHas('logs', function($query) use ($startDate) {
                $query->where('checked_at', '>=', $startDate)
                    ->where('is_up', true);
            })
            ->with(['logs' => function($query) use ($startDate) {
                $query->where('checked_at', '>=', $startDate)
                    ->where('is_up', true);
            }])
            ->get()
            ->map(function($site) {
                return [
                    'name' => $site->name,
                    'avg_response_time' => round($site->logs->avg('response_time'), 2),
                ];
            })
            ->sortByDesc('avg_response_time')
            ->take($limit)
            ->values()
            ->toArray();
    }

    protected function getMostUnreliableSites(Carbon $startDate, int $limit = 5): array
    {
        return Site::whereHas('logs', function($query) use ($startDate) {
                $query->where('checked_at', '>=', $startDate);
            })
            ->with(['logs' => function($query) use ($startDate) {
                $query->where('checked_at', '>=', $startDate);
            }])
            ->get()
            ->map(function($site) {
                $totalLogs = $site->logs->count();
                $upLogs = $site->logs->where('is_up', true)->count();
                $uptime = $totalLogs > 0 ? round(($upLogs / $totalLogs) * 100, 2) : 100;
                
                return [
                    'name' => $site->name,
                    'uptime' => $uptime,
                    'total_checks' => $totalLogs,
                    'failed_checks' => $totalLogs - $upLogs,
                ];
            })
            ->filter(fn($site) => $site['total_checks'] > 0)
            ->sortBy('uptime')
            ->take($limit)
            ->values()
            ->toArray();
    }
}
