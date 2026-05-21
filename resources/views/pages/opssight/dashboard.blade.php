@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            OpsSight Monitoring Center
        </h2>
        
        <form method="GET" action="{{ route('opssight.dashboard') }}" class="flex flex-wrap items-center gap-3 bg-white dark:bg-boxdark p-3 rounded-lg shadow-sm border border-stroke dark:border-strokedark">
            <select name="severity" class="rounded border border-stroke bg-transparent py-1.5 px-3 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input">
                <option value="">All Severities</option>
                <option value="CRITICAL" {{ request('severity') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                <option value="HIGH" {{ request('severity') == 'HIGH' ? 'selected' : '' }}>HIGH</option>
                <option value="MEDIUM" {{ request('severity') == 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                <option value="LOW" {{ request('severity') == 'LOW' ? 'selected' : '' }}>LOW</option>
            </select>
            
            <select name="status" class="rounded border border-stroke bg-transparent py-1.5 px-3 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input">
                <option value="">All Statuses</option>
                <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>OPEN</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                <option value="RESOLVED" {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>RESOLVED</option>
                <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
            </select>
            
            <select name="category" class="rounded border border-stroke bg-transparent py-1.5 px-3 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search incidents..." class="rounded border border-stroke bg-transparent py-1.5 px-3 pl-8 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 fill-body" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.16666 3.33332C5.945 3.33332 3.33332 5.945 3.33332 9.16666C3.33332 12.3883 5.945 15 9.16666 15C12.3883 15 15 12.3883 15 9.16666C15 5.945 12.3883 3.33332 9.16666 3.33332ZM1.66666 9.16666C1.66666 5.02452 5.02452 1.66666 9.16666 1.66666C13.3088 1.66666 16.6667 5.02452 16.6667 9.16666C16.6667 11.1464 15.897 12.9463 14.6475 14.288L17.9226 17.5631C18.248 17.8885 18.248 18.4162 17.9226 18.7416C17.5972 19.067 17.0695 19.067 16.7441 18.7416L13.433 15.4305C12.2155 16.2307 10.7441 16.6667 9.16666 16.6667C5.02452 16.6667 1.66666 13.3088 1.66666 9.16666Z" fill=""/>
                </svg>
            </div>
            
            <button type="submit" class="inline-flex items-center justify-center rounded bg-primary py-1.5 px-4 text-center font-medium text-white hover:bg-opacity-90">
                Filter
            </button>
        </form>
    </div>

    <!-- 1. SUMMARY METRICS CARDS -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
        <!-- Total Incidents -->
        <div class="rounded-sm border border-stroke bg-white py-6 px-7.5 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
                <svg class="fill-primary dark:fill-white" width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 0H1C0.4 0 0 0.4 0 1V15C0 15.6 0.4 16 1 16H21C21.6 16 22 15.6 22 15V1C22 0.4 21.6 0 21 0ZM20 14H2V2H20V14Z" fill=""/>
                    <path d="M6 10H16V12H6V10Z" fill=""/>
                    <path d="M6 4H16V8H6V4Z" fill=""/>
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-black dark:text-white">{{ number_format($totalIncidents) }}</h4>
                    <span class="text-sm font-medium">Total Incidents</span>
                </div>
            </div>
        </div>
        
        <!-- Open Incidents -->
        <div class="rounded-sm border border-stroke bg-white py-6 px-7.5 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                <svg class="fill-primary" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 19.93C7.05 19.43 4 16.05 4 12C4 7.95 7.05 4.57 11 4.07V19.93ZM13 4.07C16.95 4.57 20 7.95 20 12C20 16.05 16.95 19.43 13 19.93V4.07Z"/>
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-primary">{{ number_format($openIncidents) }}</h4>
                    <span class="text-sm font-medium">Open Incidents</span>
                </div>
            </div>
        </div>

        <!-- Critical Incidents -->
        <div class="rounded-sm border-t-4 border-t-danger border border-stroke bg-white py-6 px-7.5 shadow-default dark:border-strokedark dark:bg-boxdark relative overflow-hidden">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-danger opacity-10"></div>
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-danger/10">
                <svg class="fill-danger" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L1 21H23L12 2ZM12 6L19.53 19H4.47L12 6ZM11 10V14H13V10H11ZM11 16V18H13V16H11Z"/>
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-danger">{{ number_format($criticalIncidents) }}</h4>
                    <span class="text-sm font-medium text-danger">Critical Incidents</span>
                </div>
                <span class="flex items-center gap-1 text-sm font-medium text-danger bg-danger/10 px-2 py-0.5 rounded">Action Required</span>
            </div>
        </div>

        <!-- Resolved Incidents -->
        <div class="rounded-sm border border-stroke bg-white py-6 px-7.5 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-success/10">
                <svg class="fill-success" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"/>
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-success">{{ number_format($resolvedIncidents) }}</h4>
                    <span class="text-sm font-medium">Resolved Incidents</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-7.5">
        <!-- 2. CRITICAL INCIDENT PANEL (Attention Logic) -->
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-sm border border-danger/50 bg-danger/5 shadow-default dark:border-danger/30 dark:bg-danger/10 h-full flex flex-col">
                <div class="border-b border-danger/20 py-4 px-6.5 flex justify-between items-center bg-danger/10 rounded-t-sm">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-danger"></span>
                        </span>
                        <h3 class="font-bold text-danger dark:text-white uppercase tracking-wider">
                            Urgent: Unresolved Critical Incidents
                        </h3>
                    </div>
                </div>
                <div class="p-6.5 flex-1">
                    @if($criticalPanelIncidents->count() > 0)
                        <div class="flex flex-col gap-3">
                            @foreach($criticalPanelIncidents as $incident)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-white dark:bg-boxdark rounded border border-danger/20 shadow-sm hover:border-danger transition-colors">
                                    <div class="flex flex-col gap-1 mb-3 sm:mb-0">
                                        <h4 class="font-semibold text-black dark:text-white">{{ $incident->title }}</h4>
                                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span>ID: #{{ $incident->id }}</span>
                                            <span>&bull;</span>
                                            <span>{{ \Carbon\Carbon::parse($incident->incident_date)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex rounded bg-danger/10 py-1 px-3 text-sm font-medium text-danger">CRITICAL</span>
                                        <span class="inline-flex rounded bg-warning/10 py-1 px-3 text-sm font-medium text-warning">{{ $incident->status }}</span>
                                        @if($incident->assigned_operator_name)
                                            <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 py-1 px-3 rounded">
                                                <svg class="fill-current w-4 h-4 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z"/></svg>
                                                <span class="text-sm font-medium text-black dark:text-white">{{ $incident->assigned_operator_name }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex rounded bg-gray-100 dark:bg-gray-800 py-1 px-3 text-sm font-medium text-gray-500">Unassigned</span>
                                        @endif
                                        <a href="{{ route('incidents.show', $incident->id) }}" class="inline-flex rounded bg-danger py-1 px-3 text-sm font-medium text-white hover:bg-danger/90 transition-colors">Action</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full min-h-[200px] text-success">
                            <svg class="w-12 h-12 mb-3 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"/></svg>
                            <h4 class="text-lg font-semibold">System Stable</h4>
                            <p class="text-sm">No unresolved critical incidents at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 5. SEVERITY DISTRIBUTION CHART -->
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-sm border border-stroke bg-white px-5 pt-7.5 pb-5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 h-full">
                <div class="mb-3 justify-between gap-4 sm:flex">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Severity Distribution</h4>
                    </div>
                </div>
                <div class="mb-2">
                    <div id="severityChart" class="mx-auto flex justify-center"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-7.5">
        <!-- 3. RECENT INCIDENTS TABLE -->
        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark h-full flex flex-col">
                <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark flex justify-between items-center">
                    <h3 class="font-medium text-black dark:text-white">
                        Recent Incidents
                    </h3>
                    <a href="{{ route('incidents.index') }}" class="text-sm font-medium text-primary hover:underline">View All</a>
                </div>
                <div class="p-6.5 flex-1 flex flex-col">
                    <div class="max-w-full overflow-x-auto flex-1">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                    <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">ID</th>
                                    <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white">Incident</th>
                                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Severity</th>
                                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Status</th>
                                    <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">Assignee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentIncidents as $incident)
                                <tr>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <p class="text-black dark:text-white">#{{ $incident->id }}</p>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <a href="{{ route('incidents.show', $incident->id) }}" class="font-medium text-primary hover:underline">
                                            {{ \Illuminate\Support\Str::limit($incident->title, 40) }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">{{ $incident->category_name ?? 'N/A' }} &bull; {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}</p>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        @php
                                            $sevClass = match($incident->severity) {
                                                'CRITICAL' => 'bg-danger/10 text-danger',
                                                'HIGH' => 'bg-orange-500/10 text-orange-500',
                                                'MEDIUM' => 'bg-warning/10 text-warning',
                                                'LOW' => 'bg-success/10 text-success',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="inline-flex rounded-full py-1 px-3 text-sm font-medium {{ $sevClass }}">
                                            {{ $incident->severity }}
                                        </span>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        @php
                                            $statusClass = match($incident->status) {
                                                'OPEN' => 'bg-primary/10 text-primary',
                                                'IN_PROGRESS' => 'bg-warning/10 text-warning',
                                                'RESOLVED' => 'bg-success/10 text-success',
                                                'CLOSED' => 'bg-meta-5/10 text-meta-5',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="inline-flex rounded-full py-1 px-3 text-sm font-medium {{ $statusClass }}">
                                            {{ str_replace('_', ' ', $incident->status) }}
                                        </span>
                                    </td>
                                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                                        <p class="text-black dark:text-white">{{ $incident->assigned_operator_name ?? 'Unassigned' }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $recentIncidents->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. INCIDENT STATUS SUMMARY CHART -->
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-sm border border-stroke bg-white px-5 pt-7.5 pb-5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 h-full">
                <div class="mb-3 justify-between gap-4 sm:flex">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Status Overview</h4>
                    </div>
                </div>
                <div class="mb-2 mt-8">
                    <div id="statusChart" class="mx-auto flex justify-center"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. RECENT AUDIT LOGS -->
    <div class="col-span-12">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark flex justify-between items-center">
                <h3 class="font-medium text-black dark:text-white">
                    Recent Audit Activity
                </h3>
                <a href="{{ route('audit-logs.index') }}" class="text-sm font-medium text-primary hover:underline">View All Logs</a>
            </div>
            <div class="p-6.5">
                <div class="max-w-full overflow-x-auto flex-1">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="py-4 px-4 font-medium text-black dark:text-white">Timestamp</th>
                                <th class="py-4 px-4 font-medium text-black dark:text-white">User</th>
                                <th class="py-4 px-4 font-medium text-black dark:text-white">Action</th>
                                <th class="py-4 px-4 font-medium text-black dark:text-white">Table/Resource</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAuditLogs as $log)
                            <tr>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-black dark:text-white text-sm">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-black dark:text-white font-medium">{{ $log->user_name ?? 'System' }}</p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    @php
                                        $actionClass = match(strtoupper($log->action)) {
                                            'CREATE' => 'bg-success/10 text-success',
                                            'UPDATE' => 'bg-warning/10 text-warning',
                                            'DELETE' => 'bg-danger/10 text-danger',
                                            default => 'bg-primary/10 text-primary'
                                        };
                                    @endphp
                                    <span class="inline-flex rounded py-1 px-3 text-xs font-medium uppercase {{ $actionClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-black dark:text-white font-mono text-sm">{{ $log->table_name }}</p>
                                </td>
                            </tr>
                            @endforeach
                            @if($recentAuditLogs->isEmpty())
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">No recent audit logs found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Severity Chart
    const severityData = {
        CRITICAL: {{ $severityChart['CRITICAL'] ?? 0 }},
        HIGH: {{ $severityChart['HIGH'] ?? 0 }},
        MEDIUM: {{ $severityChart['MEDIUM'] ?? 0 }},
        LOW: {{ $severityChart['LOW'] ?? 0 }}
    };

    const severityChartOptions = {
        series: [severityData.CRITICAL, severityData.HIGH, severityData.MEDIUM, severityData.LOW],
        chart: {
            type: 'donut',
            width: 380,
            fontFamily: 'Satoshi, sans-serif'
        },
        colors: ['#DC3545', '#F97316', '#F59E0B', '#10B981'],
        labels: ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW'],
        legend: {
            show: true,
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    background: 'transparent'
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        responsive: [{
            breakpoint: 640,
            options: {
                chart: {
                    width: 300
                }
            }
        }]
    };

    if (document.getElementById('severityChart')) {
        const sevChart = new ApexCharts(document.getElementById('severityChart'), severityChartOptions);
        sevChart.render();
    }

    // Status Chart
    const statusData = {
        OPEN: {{ $statusChart['OPEN'] ?? 0 }},
        IN_PROGRESS: {{ $statusChart['IN_PROGRESS'] ?? 0 }},
        RESOLVED: {{ $statusChart['RESOLVED'] ?? 0 }},
        CLOSED: {{ $statusChart['CLOSED'] ?? 0 }}
    };

    const statusChartOptions = {
        series: [{
            name: 'Incidents',
            data: [statusData.OPEN, statusData.IN_PROGRESS, statusData.RESOLVED, statusData.CLOSED]
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false },
            fontFamily: 'Satoshi, sans-serif'
        },
        colors: ['#3C50E0', '#F59E0B', '#10B981', '#64748B'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: ['OPEN', 'IN PROGRESS', 'RESOLVED', 'CLOSED'],
            labels: {
                style: {
                    colors: ['#3C50E0', '#F59E0B', '#10B981', '#64748B'],
                    fontSize: '12px'
                }
            }
        }
    };

    if (document.getElementById('statusChart')) {
        const statChart = new ApexCharts(document.getElementById('statusChart'), statusChartOptions);
        statChart.render();
    }
});
</script>
@endpush
