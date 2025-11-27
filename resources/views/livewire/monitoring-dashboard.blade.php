<div class="space-y-6">
    <!-- Header with Time Range Filter -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Dashboard</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Real-time uptime monitoring analytics</p>
        </div>
        
        <div class="flex gap-2">
            <button wire:click="$set('timeRange', '24h')" 
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $timeRange === '24h' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                24 Hours
            </button>
            <button wire:click="$set('timeRange', '7d')" 
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $timeRange === '7d' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                7 Days
            </button>
            <button wire:click="$set('timeRange', '30d')" 
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $timeRange === '30d' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                30 Days
            </button>
            <button wire:click="$set('timeRange', '90d')" 
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $timeRange === '90d' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                90 Days
            </button>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sites -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sites</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $analytics['totalSites'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $analytics['activeSites'] }} active
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overall Uptime -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overall Uptime</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($analytics['overallUptime'], 2) }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $analytics['totalChecks'] }} checks
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sites Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sites Status</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $analytics['sitesUp'] }}</p>
                        <span class="text-gray-400">/</span>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $analytics['sitesDown'] }}</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        Up / Down
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Response Time -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Response Time</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $analytics['averageResponseTime'] ? number_format($analytics['averageResponseTime'], 0) : 'N/A' }}
                        @if($analytics['averageResponseTime'])
                            <span class="text-lg">ms</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $analytics['failedChecks'] }} failed
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Uptime Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Uptime Percentage</h3>
            <div class="h-64">
                <canvas id="uptimeChart"></canvas>
            </div>
        </div>

        <!-- Response Time Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Response Time (ms)</h3>
            <div class="h-64">
                <canvas id="responseTimeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Slowest Sites -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Slowest Sites</h3>
            <div class="space-y-3">
                @forelse($analytics['topSlowestSites'] as $site)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $site['name'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">
                                {{ number_format($site['avg_response_time'], 0) }} ms
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Most Unreliable Sites -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Most Unreliable Sites</h3>
            <div class="space-y-3">
                @forelse($analytics['mostUnreliableSites'] as $site)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $site['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $site['failed_checks'] }} failures</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold {{ $site['uptime'] >= 99 ? 'text-green-600 dark:text-green-400' : ($site['uptime'] >= 95 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                {{ number_format($site['uptime'], 2) }}%
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Current Sites Status -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Sites Status</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Site</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Response Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Check</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uptime</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sites as $site)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $site->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $site->url }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($site->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $site->is_up ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $site->is_up ? 'Up' : 'Down' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $site->last_response_time ? number_format($site->last_response_time, 0) . ' ms' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $site->last_checked_at ? $site->last_checked_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $site->uptime_percentage >= 99 ? 'bg-green-600' : ($site->uptime_percentage >= 95 ? 'bg-yellow-600' : 'bg-red-600') }}" 
                                             style="width: {{ $site->uptime_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($site->uptime_percentage, 2) }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No sites configured yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9CA3AF' : '#6B7280';
            const gridColor = isDark ? '#374151' : '#E5E7EB';

            // Uptime Chart
            const uptimeCtx = document.getElementById('uptimeChart').getContext('2d');
            new Chart(uptimeCtx, {
                type: 'line',
                data: {
                    labels: @json($analytics['chartData']['labels']),
                    datasets: [{
                        label: 'Uptime %',
                        data: @json($analytics['chartData']['uptime']),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });

            // Response Time Chart
            const responseCtx = document.getElementById('responseTimeChart').getContext('2d');
            new Chart(responseCtx, {
                type: 'line',
                data: {
                    labels: @json($analytics['responseTimeChart']['labels']),
                    datasets: [{
                        label: 'Average',
                        data: @json($analytics['responseTimeChart']['average']),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        });

        // Update charts when time range changes
        Livewire.on('chartDataUpdated', () => {
            location.reload();
        });
    </script>
</div>
