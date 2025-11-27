<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class SiteLog extends Model
{
    protected $fillable = [
        'site_id',
        'is_up',
        'response_time',
        'status_code',
        'error_message',
        'checked_at',
    ];

    protected $casts = [
        'is_up' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    // Scopes
    public function scopeUptime(Builder $query): Builder
    {
        return $query->where('is_up', true);
    }

    public function scopeDowntime(Builder $query): Builder
    {
        return $query->where('is_up', false);
    }

    public function scopeInLastHours(Builder $query, int $hours): Builder
    {
        return $query->where('checked_at', '>=', Carbon::now()->subHours($hours));
    }

    public function scopeInLastDays(Builder $query, int $days): Builder
    {
        return $query->where('checked_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeForSite(Builder $query, int $siteId): Builder
    {
        return $query->where('site_id', $siteId);
    }

    public function scopeRecent(Builder $query, int $limit = 10): Builder
    {
        return $query->orderBy('checked_at', 'desc')->limit($limit);
    }

    public function scopeSlowResponses(Builder $query, int $threshold = 1000): Builder
    {
        return $query->where('response_time', '>', $threshold);
    }

    public function scopeWithErrors(Builder $query): Builder
    {
        return $query->whereNotNull('error_message');
    }

    // Accessors
    public function getStatusAttribute(): string
    {
        return $this->is_up ? 'Up' : 'Down';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->is_up ? 'green' : 'red';
    }

    public function getResponseTimeFormattedAttribute(): string
    {
        if (!$this->response_time) {
            return 'N/A';
        }

        if ($this->response_time < 1000) {
            return $this->response_time . ' ms';
        }

        return number_format($this->response_time / 1000, 2) . ' s';
    }

    public function getCheckedAtHumanAttribute(): string
    {
        return $this->checked_at?->diffForHumans() ?? 'Never';
    }

    public function getIsSlowAttribute(): bool
    {
        return $this->response_time > 1000; // Consider slow if > 1 second
    }

    public function getIsCriticalAttribute(): bool
    {
        return $this->response_time > 5000; // Critical if > 5 seconds
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_up 
            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
    }

    public function getPerformanceLevelAttribute(): string
    {
        if (!$this->is_up) {
            return 'down';
        }

        if ($this->response_time < 500) {
            return 'excellent';
        } elseif ($this->response_time < 1000) {
            return 'good';
        } elseif ($this->response_time < 3000) {
            return 'moderate';
        } else {
            return 'poor';
        }
    }

    public function getPerformanceColorAttribute(): string
    {
        return match($this->performance_level) {
            'excellent' => 'green',
            'good' => 'blue',
            'moderate' => 'yellow',
            'poor' => 'orange',
            'down' => 'red',
            default => 'gray',
        };
    }

    // Static Methods
    public static function logCheck(
        int $siteId,
        bool $isUp,
        ?int $responseTime = null,
        ?int $statusCode = null,
        ?string $errorMessage = null
    ): self {
        return self::create([
            'site_id' => $siteId,
            'is_up' => $isUp,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => Carbon::now(),
        ]);
    }

    public static function getUptimePercentage(int $siteId, int $days = 30): float
    {
        $logs = self::forSite($siteId)->inLastDays($days)->get();
        
        if ($logs->isEmpty()) {
            return 100.0;
        }

        $upCount = $logs->where('is_up', true)->count();
        return round(($upCount / $logs->count()) * 100, 2);
    }

    public static function getAverageResponseTime(int $siteId, int $days = 30): ?float
    {
        return self::forSite($siteId)
            ->inLastDays($days)
            ->uptime()
            ->avg('response_time');
    }
}
