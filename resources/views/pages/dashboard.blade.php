@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    loading: false,
    latency: '---',
    async refreshDashboard() {
        this.loading = true;
        window.location.reload();
    },
    async measureLatency() {
        const start = performance.now();
        try {
            await fetch(window.location.origin + '/ping', { method: 'HEAD', cache: 'no-cache' });
            const end = performance.now();
            this.latency = Math.round(end - start) + 'ms';
        } catch (e) {
            this.latency = 'Offline';
        }
    }
}" x-init="measureLatency(); setInterval(() => measureLatency(), 5000)">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Oversight</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total control over your security infrastructure and API integrations.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="refreshDashboard()" 
                    class="p-2 text-gray-500 hover:text-brand-500 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 transition-all hover:shadow-sm" 
                    title="Refresh Data">
                <svg :class="loading ? 'animate-spin' : ''" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
            <a href="{{ route('api-keys.index') }}" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-brand-500/20">
                Manage Keys
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Certificates -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Certificates</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalCertificates }}</span>
                <span class="text-xs text-green-500 flex items-center gap-1 mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.395 6.227a.75.75 0 011.082.022l3.992 4.497a.75.75 0 01-1.104 1.012l-3.469-3.908-4.496 3.992a.75.75 0 01-1.012-1.104l5.007-4.511z" clip-rule="evenodd" />
                    </svg>
                    {{ $activeCertificates }} Active Now
                </span>
            </div>
        </div>

        <!-- Card 2: API Keys -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11 9 13.536 7.464 12 4.929 14.536V17h2.472l4.243-4.243a6 6 0 018.828-5.743zM16.5 13.5V18h6v-4.5h-6z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Manageable API Keys</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalApiKeys }}</span>
                <span class="text-xs text-blue-500 flex items-center gap-1 mt-2">
                    Latest usage: {{ $recentApiActivity->first()->last_used_at?->diffForHumans() ?? 'None' }}
                </span>
            </div>
        </div>

        <!-- Card 3: Expiring Soon -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Expiring Soon</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $expiringSoonCount }}</span>
                <span class="text-xs text-orange-500 flex items-center gap-1 mt-2">
                    Action required within 14 days
                </span>
            </div>
        </div>

        <!-- Card 4: System Health -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Node Status</span>
                <span class="text-3xl font-bold mt-1" :class="latency === 'Offline' ? 'text-red-500' : 'text-green-500'" x-text="latency === 'Offline' ? 'Offline' : 'Operational'">Operational</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-2">
                    Latency: <span x-text="latency"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Analytics Chart -->
        <div class="lg:col-span-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-gray-900 dark:text-white">Certificate Issuance Trends</h3>
                <span class="text-xs font-medium text-gray-400">Last 6 Months</span>
            </div>
            
            <div class="flex-1 flex items-end justify-between gap-2 min-h-[200px] px-2">
                @foreach($issuanceData as $index => $count)
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <div class="relative w-full flex items-end justify-center">
                            @php 
                                $max = max($issuanceData) ?: 1;
                                $percentage = ($count / $max) * 100;
                            @endphp
                            <div class="w-full max-w-[40px] bg-brand-500/20 dark:bg-brand-500/10 rounded-t-lg group-hover:bg-brand-500/30 transition-all cursor-pointer relative" 
                                 style="height: {{ max($percentage, 5) }}%;">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 dark:bg-gray-700 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{ $count }}
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $months[$index] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent API Activity -->
        <div class="lg:col-span-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6">Recent API Activity</h3>
            <div class="space-y-4">
                @forelse($recentApiActivity as $activity)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11 9 13.536 7.464 12 4.929 14.536V17h2.472l4.243-4.243a6 6 0 018.828-5.743zM16.5 13.5V18h6v-4.5h-6z" />
                        </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $activity->name }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Used {{ $activity->last_used_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-400">No recent activity detected.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Latest Certificates -->
        <div class="lg:col-span-12 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white">Recently Issued Certificates</h3>
                <a href="#" class="text-xs font-bold text-brand-500 hover:text-brand-600 uppercase tracking-wider">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4">Common Name</th>
                            <th class="px-6 py-4">Organization</th>
                            <th class="px-6 py-4">Issued At</th>
                            <th class="px-6 py-4">Expires</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentCertificates as $cert)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $cert->common_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $cert->organization }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $cert->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $cert->valid_to->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($cert->valid_to > now())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500 uppercase">Valid</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500 uppercase">Expired</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No certificates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
