@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- ─── FILTER BAR ──────────────────────────────────────────────── --}}
    <div class="col-span-12">
        <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-semibold text-black dark:text-white">
                OpsSight Monitoring Center
            </h2>

            <form method="GET" action="{{ route('opssight.dashboard') }}"
                  class="flex flex-wrap items-center gap-3 rounded-[10px] border border-stroke bg-white p-3 shadow-sm dark:border-strokedark dark:bg-boxdark">

                <select name="severity"
                        class="rounded border border-stroke bg-transparent py-1.5 px-3 text-sm outline-none transition focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                    <option value="">All Severities</option>
                    <option value="CRITICAL" {{ request('severity') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                    <option value="HIGH"     {{ request('severity') == 'HIGH'     ? 'selected' : '' }}>HIGH</option>
                    <option value="MEDIUM"   {{ request('severity') == 'MEDIUM'   ? 'selected' : '' }}>MEDIUM</option>
                    <option value="LOW"      {{ request('severity') == 'LOW'      ? 'selected' : '' }}>LOW</option>
                </select>

                <select name="status"
                        class="rounded border border-stroke bg-transparent py-1.5 px-3 text-sm outline-none transition focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                    <option value="">All Statuses</option>
                    <option value="OPEN"        {{ request('status') == 'OPEN'        ? 'selected' : '' }}>OPEN</option>
                    <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                    <option value="RESOLVED"    {{ request('status') == 'RESOLVED'    ? 'selected' : '' }}>RESOLVED</option>
                    <option value="CLOSED"      {{ request('status') == 'CLOSED'      ? 'selected' : '' }}>CLOSED</option>
                </select>

                <select name="category"
                        class="rounded border border-stroke bg-transparent py-1.5 px-3 text-sm outline-none transition focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search incidents..."
                           class="rounded border border-stroke bg-transparent py-1.5 pl-8 pr-3 text-sm outline-none transition focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 fill-body" width="16" height="16"
                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z"
                              fill=""/>
                    </svg>
                </div>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded bg-primary py-1.5 px-4 text-sm font-medium text-white hover:bg-opacity-90 transition">
                    Filter
                </button>
            </form>
        </div>
    </div>

    {{-- ─── SUMMARY METRIC CARDS ────────────────────────────────────── --}}
    <div class="col-span-12 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

        {{-- Total Incidents --}}
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
                <svg class="fill-primary dark:fill-white" width="22" height="22" viewBox="0 0 22 16"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 0H1C.4 0 0 .4 0 1v14c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1Zm-1 14H2V2h18v12Z"/>
                    <path d="M6 10h10v2H6v-2ZM6 4h10v4H6V4Z"/>
                </svg>
            </div>
            <div class="mt-5">
                <h4 class="text-2xl font-bold text-black dark:text-white">{{ number_format($totalIncidents) }}</h4>
                <span class="mt-1 text-sm font-medium text-body">Total Incidents</span>
            </div>
        </div>

        {{-- Open Incidents --}}
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                <svg class="fill-primary" width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-1 17.93C7.05 19.43 4 16.05 4 12S7.05 4.57 11 4.07v15.86Zm2-15.86C16.95 4.57 20 7.95 20 12s-3.05 7.43-7 7.93V4.07Z"/>
                </svg>
            </div>
            <div class="mt-5">
                <h4 class="text-2xl font-bold text-primary">{{ number_format($openIncidents) }}</h4>
                <span class="mt-1 text-sm font-medium text-body">Open Incidents</span>
            </div>
        </div>

        {{-- Critical Incidents --}}
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <svg class="fill-danger" width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2 1 21h22L12 2Zm0 4 7.53 13H4.47L12 6Zm-1 4v4h2v-4h-2Zm0 6v2h2v-2h-2Z"/>
                </svg>
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <h4 class="text-2xl font-bold text-danger">{{ number_format($criticalIncidents) }}</h4>
                    <span class="mt-1 text-sm font-medium text-body">Critical Incidents</span>
                </div>
                <span class="flex items-center rounded bg-danger/10 px-2.5 py-1 text-xs font-medium text-danger">
                    Action Required
                </span>
            </div>
        </div>

        {{-- Resolved Incidents --}}
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                <svg class="fill-success" width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9Z"/>
                </svg>
            </div>
            <div class="mt-5">
                <h4 class="text-2xl font-bold text-success">{{ number_format($resolvedIncidents) }}</h4>
                <span class="mt-1 text-sm font-medium text-body">Resolved Incidents</span>
            </div>
        </div>
    </div>

    {{-- ─── CRITICAL PANEL + SEVERITY CHART ────────────────────────── --}}
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-[10px] border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark h-full flex flex-col">
            {{-- Header --}}
            <div class="border-b border-stroke px-6 py-4 dark:border-strokedark">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-danger"></span>
                    </span>
                    <h4 class="text-base font-semibold text-black dark:text-white uppercase tracking-wide">
                        Urgent: Unresolved Critical Incidents
                    </h4>
                </div>
            </div>
            {{-- Body --}}
            <div class="flex-1 p-6">
                @if($criticalPanelIncidents->count() > 0)
                    <div class="flex flex-col gap-3">
                        @foreach($criticalPanelIncidents as $incident)
                            <div class="flex flex-col gap-3 rounded-[10px] border border-stroke p-4 transition hover:border-danger dark:border-strokedark sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-col gap-1">
                                    <h5 class="font-semibold text-black dark:text-white">{{ $incident->title }}</h5>
                                    <p class="text-sm text-body">
                                        ID: #{{ $incident->id }}
                                        &bull;
                                        {{ \Carbon\Carbon::parse($incident->incident_date)->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-danger/10 px-3 py-1 text-sm font-medium text-danger">CRITICAL</span>
                                    <span class="inline-flex rounded-full bg-warning/10 px-3 py-1 text-sm font-medium text-warning">{{ $incident->status }}</span>
                                    @if($incident->assigned_operator_name)
                                        <div class="flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 dark:bg-meta-4">
                                            <svg class="h-4 w-4 fill-body" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $incident->assigned_operator_name }}</span>
                                        </div>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-body dark:bg-meta-4">Unassigned</span>
                                    @endif
                                    <a href="{{ route('incidents.show', $incident->id) }}"
                                       class="inline-flex rounded-full bg-danger px-3 py-1 text-sm font-medium text-white transition hover:bg-opacity-90">
                                        Action
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex min-h-[200px] flex-col items-center justify-center gap-3 text-success">
                        <svg class="h-12 w-12 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9Z"/>
                        </svg>
                        <div class="text-center">
                            <h4 class="text-lg font-semibold">System Stable</h4>
                            <p class="text-sm">No unresolved critical incidents at this time.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark h-full">
            <h4 class="mb-6 text-xl font-semibold text-black dark:text-white">Severity Distribution</h4>
            <div id="severityChart" class="mx-auto flex justify-center"></div>
        </div>
    </div>

    {{-- ─── RECENT INCIDENTS TABLE + STATUS CHART ───────────────────── --}}
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-[10px] border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark h-full flex flex-col">
            <div class="border-b border-stroke px-6 py-4 dark:border-strokedark flex items-center justify-between">
                <h4 class="text-base font-semibold text-black dark:text-white">Recent Incidents</h4>
                <a href="{{ route('incidents.index') }}" class="text-sm font-medium text-primary hover:underline">View All</a>
            </div>
            <div class="flex-1 p-6">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="min-w-[80px] py-4 px-4 text-sm font-medium text-black dark:text-white">ID</th>
                                <th class="min-w-[200px] py-4 px-4 text-sm font-medium text-black dark:text-white">Incident</th>
                                <th class="min-w-[110px] py-4 px-4 text-sm font-medium text-black dark:text-white">Severity</th>
                                <th class="min-w-[110px] py-4 px-4 text-sm font-medium text-black dark:text-white">Status</th>
                                <th class="min-w-[140px] py-4 px-4 text-sm font-medium text-black dark:text-white">Assignee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentIncidents as $incident)
                            <tr>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-sm text-black dark:text-white">#{{ $incident->id }}</p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <a href="{{ route('incidents.show', $incident->id) }}"
                                       class="font-medium text-primary hover:underline">
                                        {{ \Illuminate\Support\Str::limit($incident->title, 40) }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-body">
                                        {{ $incident->category_name ?? 'N/A' }}
                                        &bull;
                                        {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}
                                    </p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    @php
                                        $sevClass = match($incident->severity) {
                                            'CRITICAL' => 'bg-danger/10 text-danger',
                                            'HIGH'     => 'bg-orange-500/10 text-orange-500',
                                            'MEDIUM'   => 'bg-warning/10 text-warning',
                                            'LOW'      => 'bg-success/10 text-success',
                                            default    => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $sevClass }}">
                                        {{ $incident->severity }}
                                    </span>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    @php
                                        $statusClass = match($incident->status) {
                                            'OPEN'        => 'bg-primary/10 text-primary',
                                            'IN_PROGRESS' => 'bg-warning/10 text-warning',
                                            'RESOLVED'    => 'bg-success/10 text-success',
                                            'CLOSED'      => 'bg-meta-5/10 text-meta-5',
                                            default       => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $incident->status) }}
                                    </span>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-sm text-black dark:text-white">
                                        {{ $incident->assigned_operator_name ?? 'Unassigned' }}
                                    </p>
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

    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-[10px] border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark h-full">
            <h4 class="mb-6 text-xl font-semibold text-black dark:text-white">Status Overview</h4>
            <div id="statusChart" class="mx-auto"></div>
        </div>
    </div>

    {{-- ─── RECENT AUDIT LOGS ───────────────────────────────────────── --}}
    <div class="col-span-12">
        <div class="rounded-[10px] border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6 py-4 dark:border-strokedark flex items-center justify-between">
                <h4 class="text-base font-semibold text-black dark:text-white">Recent Audit Activity</h4>
                <a href="{{ route('audit-logs.index') }}" class="text-sm font-medium text-primary hover:underline">View All Logs</a>
            </div>
            <div class="p-6">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="py-4 px-4 text-sm font-medium text-black dark:text-white">Timestamp</th>
                                <th class="py-4 px-4 text-sm font-medium text-black dark:text-white">User</th>
                                <th class="py-4 px-4 text-sm font-medium text-black dark:text-white">Action</th>
                                <th class="py-4 px-4 text-sm font-medium text-black dark:text-white">Table / Resource</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAuditLogs as $log)
                            <tr>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="text-sm text-black dark:text-white">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}
                                    </p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="font-medium text-black dark:text-white">{{ $log->user_name ?? 'System' }}</p>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    @php
                                        $actionClass = match(strtoupper($log->action)) {
                                            'CREATE' => 'bg-success/10 text-success',
                                            'UPDATE' => 'bg-warning/10 text-warning',
                                            'DELETE' => 'bg-danger/10 text-danger',
                                            default  => 'bg-primary/10 text-primary',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium uppercase {{ $actionClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="border-b border-[#eee] py-4 px-4 dark:border-strokedark">
                                    <p class="font-mono text-sm text-black dark:text-white">{{ $log->table_name }}</p>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-body">No recent audit logs found.</td>
                            </tr>
                            @endforelse
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

    /* ── Severity Donut Chart ────────────────────────────────── */
    const severityData = {
        CRITICAL : {{ $severityChart['CRITICAL'] ?? 0 }},
        HIGH     : {{ $severityChart['HIGH']     ?? 0 }},
        MEDIUM   : {{ $severityChart['MEDIUM']   ?? 0 }},
        LOW      : {{ $severityChart['LOW']      ?? 0 }}
    };

    new ApexCharts(document.getElementById('severityChart'), {
        series  : [severityData.CRITICAL, severityData.HIGH, severityData.MEDIUM, severityData.LOW],
        chart   : { type: 'donut', width: 340, fontFamily: 'Satoshi, sans-serif' },
        colors  : ['#DC3545', '#F97316', '#F59E0B', '#10B981'],
        labels  : ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW'],
        legend  : { show: true, position: 'bottom' },
        plotOptions: { pie: { donut: { size: '65%', background: 'transparent' } } },
        dataLabels: { enabled: false },
        responsive: [{ breakpoint: 640, options: { chart: { width: 280 } } }]
    }).render();

    /* ── Status Bar Chart ────────────────────────────────────── */
    const statusData = {
        OPEN        : {{ $statusChart['OPEN']        ?? 0 }},
        IN_PROGRESS : {{ $statusChart['IN_PROGRESS'] ?? 0 }},
        RESOLVED    : {{ $statusChart['RESOLVED']    ?? 0 }},
        CLOSED      : {{ $statusChart['CLOSED']      ?? 0 }}
    };

    new ApexCharts(document.getElementById('statusChart'), {
        series: [{ name: 'Incidents', data: [statusData.OPEN, statusData.IN_PROGRESS, statusData.RESOLVED, statusData.CLOSED] }],
        chart : { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Satoshi, sans-serif' },
        colors: ['#3C50E0', '#F59E0B', '#10B981', '#64748B'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%', distributed: true } },
        dataLabels: { enabled: false },
        legend    : { show: false },
        xaxis: {
            categories: ['OPEN', 'IN PROGRESS', 'RESOLVED', 'CLOSED'],
            labels: { style: { colors: ['#3C50E0', '#F59E0B', '#10B981', '#64748B'], fontSize: '12px' } }
        }
    }).render();

});
</script>
@endpush