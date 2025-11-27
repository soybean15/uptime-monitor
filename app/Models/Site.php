<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = [
        'name',
        'url',
        'check_interval',
        'is_active',
        'is_up',
        'last_checked_at',
        'last_response_time',
        'last_status_code',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_up' => 'boolean',
        'last_checked_at' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(SiteLog::class);
    }

    public function latestLog()
    {
        return $this->hasOne(SiteLog::class)->latestOfMany();
    }

    public function getUptimePercentageAttribute(): float
    {
        $totalLogs = $this->logs()->count();
        if ($totalLogs === 0) {
            return 0;
        }

        $upLogs = $this->logs()->where('is_up', true)->count();
        return round(($upLogs / $totalLogs) * 100, 2);
    }

    public function getAverageResponseTimeAttribute(): ?int
    {
        return $this->logs()->where('is_up', true)->avg('response_time');
    }
}
