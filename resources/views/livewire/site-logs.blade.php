<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Site Check Logs</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and filter all uptime monitoring check logs</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Checks</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Successful</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($stats['up']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Failed</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($stats['down']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Uptime</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($stats['uptime_percentage'], 2) }}%</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Site name, URL, error..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Site Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site</label>
                <select wire:model.live="siteFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="up">Up Only</option>
                    <option value="down">Down Only</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                <select wire:model.live="dateRange" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="24h">Last 24 Hours</option>
                    <option value="7d">Last 7 Days</option>
                    <option value="30d">Last 30 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button wire:click="clearFilters" 
                    class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Clear
                </button>
                <button wire:click="exportLogs" 
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Site</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Response Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Checked At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Error</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->site->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $log->site->url }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->status_badge_class }}">
                                    <span class="w-2 h-2 rounded-full {{ $log->is_up ? 'bg-green-600' : 'bg-red-600' }} mr-1.5"></span>
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($log->response_time)
                                        <span class="text-sm text-gray-900 dark:text-white font-medium">
                                            {{ number_format($log->response_time, 0) }} ms
                                        </span>
                                        @if($log->is_critical)
                                            <span class="ml-2 text-xs px-2 py-0.5 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded">Critical</span>
                                        @elseif($log->is_slow)
                                            <span class="ml-2 text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded">Slow</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">N/A</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->status_code)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-mono font-medium
                                        {{ $log->status_code >= 200 && $log->status_code < 300 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $log->status_code >= 300 && $log->status_code < 400 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $log->status_code >= 400 && $log->status_code < 500 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                        {{ $log->status_code >= 500 ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                                        {{ $log->status_code }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $log->checked_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->checked_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->error_message)
                                    <div class="text-sm text-red-600 dark:text-red-400 max-w-xs truncate" title="{{ $log->error_message }}">
                                        {{ $log->error_message }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white">No logs found</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting your filters or check back later</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Results Info -->
    <div class="text-sm text-gray-600 dark:text-gray-400 text-center">
        Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs
    </div>
</div>
