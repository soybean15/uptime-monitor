<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Site;
use App\Models\SiteLog;
use Carbon\Carbon;

class SiteLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $siteFilter = '';
    public $statusFilter = 'all'; // all, up, down
    public $dateRange = '7d'; // 24h, 7d, 30d, all
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'siteFilter' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'dateRange' => ['except' => '7d'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSiteFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'siteFilter', 'statusFilter', 'dateRange']);
        $this->resetPage();
    }

    public function render()
    {
        $query = SiteLog::with('site');

        // Apply search filter
        if ($this->search) {
            $query->whereHas('site', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('url', 'like', '%' . $this->search . '%');
            })->orWhere('error_message', 'like', '%' . $this->search . '%')
              ->orWhere('status_code', 'like', '%' . $this->search . '%');
        }

        // Apply site filter
        if ($this->siteFilter) {
            $query->where('site_id', $this->siteFilter);
        }

        // Apply status filter
        if ($this->statusFilter === 'up') {
            $query->where('is_up', true);
        } elseif ($this->statusFilter === 'down') {
            $query->where('is_up', false);
        }

        // Apply date range filter
        if ($this->dateRange !== 'all') {
            $startDate = $this->getStartDate();
            $query->where('checked_at', '>=', $startDate);
        }

        // Order by most recent
        $query->orderBy('checked_at', 'desc');

        $logs = $query->paginate($this->perPage);
        $sites = Site::orderBy('name')->get();
        $stats = $this->getStats();

        return view('livewire.site-logs', [
            'logs' => $logs,
            'sites' => $sites,
            'stats' => $stats,
        ]);
    }

    protected function getStartDate(): Carbon
    {
        return match($this->dateRange) {
            '24h' => Carbon::now()->subDay(),
            '7d' => Carbon::now()->subDays(7),
            '30d' => Carbon::now()->subDays(30),
            default => Carbon::now()->subDay(),
        };
    }

    protected function getStats(): array
    {
        $query = SiteLog::query();

        // Apply the same filters for stats
        if ($this->dateRange !== 'all') {
            $query->where('checked_at', '>=', $this->getStartDate());
        }

        if ($this->siteFilter) {
            $query->where('site_id', $this->siteFilter);
        }

        $totalLogs = $query->count();
        $upLogs = (clone $query)->where('is_up', true)->count();
        $downLogs = (clone $query)->where('is_up', false)->count();
        $avgResponseTime = (clone $query)->where('is_up', true)->avg('response_time');

        return [
            'total' => $totalLogs,
            'up' => $upLogs,
            'down' => $downLogs,
            'uptime_percentage' => $totalLogs > 0 ? round(($upLogs / $totalLogs) * 100, 2) : 0,
            'avg_response_time' => $avgResponseTime ? round($avgResponseTime, 0) : null,
        ];
    }

    public function exportLogs()
    {
        // Future implementation for exporting logs to CSV
        $this->dispatch('notify', ['message' => 'Export feature coming soon!', 'type' => 'info']);
    }
}
