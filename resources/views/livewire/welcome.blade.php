<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative">
    <!-- Background Decoration -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Hero Section -->
    <div class="max-w-4xl w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="bg-gradient-to-br from-blue-500 to-cyan-500 p-3 rounded-lg shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Headline -->
        <div class="space-y-4">
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Uptime Monitoring
            </h1>
            <p class="text-xl sm:text-2xl text-slate-300 font-light">
                Real-time monitoring for your mission-critical websites
            </p>
        </div>

        <!-- Description -->
        <p class="text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Keep your websites running smoothly with continuous uptime monitoring. Track response times, identify issues instantly, and ensure your services are always available.
        </p>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-8">
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-lg p-6 hover:border-blue-500/50 transition">
                <svg class="w-8 h-8 text-blue-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h3 class="text-white font-semibold mb-2">Real-time Alerts</h3>
                <p class="text-slate-400 text-sm">Instant notifications when issues occur</p>
            </div>
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-lg p-6 hover:border-cyan-500/50 transition">
                <svg class="w-8 h-8 text-cyan-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-white font-semibold mb-2">Analytics</h3>
                <p class="text-slate-400 text-sm">Detailed insights and performance metrics</p>
            </div>
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-lg p-6 hover:border-blue-500/50 transition">
                <svg class="w-8 h-8 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-white font-semibold mb-2">Reliability</h3>
                <p class="text-slate-400 text-sm">24/7 monitoring and logging</p>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
            <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 px-8 rounded-lg transition transform hover:scale-105 shadow-lg">
                Go to Dashboard
            </a>
            <a href="{{ route('login') }}" class="border border-slate-400 text-slate-300 hover:text-white hover:border-blue-500 font-semibold py-3 px-8 rounded-lg transition">
                Sign In
            </a>
        </div>

        <!-- Status Indicator -->
        <div class="pt-12 border-t border-slate-700">
            <div class="flex items-center justify-center space-x-2 text-slate-400">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm">All systems operational</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>
