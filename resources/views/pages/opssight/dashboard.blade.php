@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- ─── FILTER BAR ──────────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                    OpsSight Monitoring Center
                </h2>

                <form method="GET" action="{{ route('opssight.dashboard') }}"
                    class="shadow-theme-xs flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03]">

                    <select name="severity"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:text-gray-400">
                        <option value="">All Severities</option>
                        <option value="CRITICAL" {{ request('severity') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                        <option value="HIGH" {{ request('severity') == 'HIGH' ? 'selected' : '' }}>HIGH</option>
                        <option value="MEDIUM" {{ request('severity') == 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                        <option value="LOW" {{ request('severity') == 'LOW' ? 'selected' : '' }}>LOW</option>
                    </select>

                    <select name="status"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:text-gray-400">
                        <option value="">All Statuses</option>
                        <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>OPEN</option>
                        <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>IN PROGRESS
                        </option>
                        <option value="RESOLVED" {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>RESOLVED</option>
                        <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
                    </select>

                    <select name="category"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:text-gray-400">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search incidents..."
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 fill-gray-400" width="16" height="16"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z"
                                fill="" />
                        </svg>
                    </div>

                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- ─── SUMMARY METRIC CARDS ────────────────────────────────────── --}}
        <div class="col-span-12 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

            {{-- Total Incidents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="fill-gray-700 dark:fill-gray-300" width="22" height="22" viewBox="0 0 22 16"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 0H1C.4 0 0 .4 0 1v14c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1Zm-1 14H2V2h18v12Z" />
                        <path d="M6 10h10v2H6v-2ZM6 4h10v4H6V4Z" />
                    </svg>
                </div>
                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Incidents</span>
                        <h4 class="text-title-sm mt-1 font-bold text-gray-800 dark:text-white/90">
                            {{ number_format($totalIncidents) }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Open Incidents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-500/10">
                    <svg class="fill-brand-500" width="22" height="22" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-1 17.93C7.05 19.43 4 16.05 4 12S7.05 4.57 11 4.07v15.86Zm2-15.86C16.95 4.57 20 7.95 20 12s-3.05 7.43-7 7.93V4.07Z" />
                    </svg>
                </div>
                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Open Incidents</span>
                        <h4 class="text-title-sm text-brand-500 mt-1 font-bold">
                            {{ number_format($openIncidents) }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Critical Incidents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-500/10">
                    <svg class="fill-error-500" width="22" height="22" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2 1 21h22L12 2Zm0 4 7.53 13H4.47L12 6Zm-1 4v4h2v-4h-2Zm0 6v2h2v-2h-2Z" />
                    </svg>
                </div>
                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Critical Incidents</span>
                        <h4 class="text-title-sm text-error-500 mt-1 font-bold">
                            {{ number_format($criticalIncidents) }}
                        </h4>
                    </div>
                    <span
                        class="bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400 flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium">
                        Action Required
                    </span>
                </div>
            </div>

            {{-- Resolved Incidents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 dark:bg-green-500/10">
                    <svg class="fill-success-500" width="22" height="22" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9Z" />
                    </svg>
                </div>
                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Resolved Incidents</span>
                        <h4 class="text-title-sm text-success-500 mt-1 font-bold">
                            {{ number_format($resolvedIncidents) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── CRITICAL PANEL + SEVERITY CHART ────────────────────────── --}}
        <div class="col-span-12 xl:col-span-8">
            <div
                class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="bg-error-400 absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"></span>
                            <span class="bg-error-500 relative inline-flex h-2.5 w-2.5 rounded-full"></span>
                        </span>
                        <h4 class="text-base font-semibold uppercase tracking-wide text-gray-800 dark:text-white/90">
                            Urgent: Unresolved Critical Incidents
                        </h4>
                    </div>
                </div>
                <div class="flex-1 p-6">
                    @if ($criticalPanelIncidents->count() > 0)
                        <div class="flex flex-col gap-3">
                            @foreach ($criticalPanelIncidents as $incident)
                                <div
                                    class="hover:border-error-300 dark:hover:border-error-500/40 flex flex-col gap-3 rounded-xl border border-gray-200 p-4 transition sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
                                    <div class="flex flex-col gap-1">
                                        <h5 class="font-semibold text-gray-800 dark:text-white/90">{{ $incident->title }}
                                        </h5>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: #{{ $incident->id }}
                                            &bull;
                                            {{ \Carbon\Carbon::parse($incident->incident_date)->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400 inline-flex rounded-full px-3 py-1 text-xs font-medium">
                                            CRITICAL
                                        </span>
                                        <span
                                            class="bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400 inline-flex rounded-full px-3 py-1 text-xs font-medium">
                                            {{ $incident->status }}
                                        </span>
                                        @if ($incident->assigned_operator_name)
                                            <div
                                                class="flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 dark:bg-gray-800">
                                                <svg class="h-3.5 w-3.5 fill-gray-500" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z" />
                                                </svg>
                                                <span
                                                    class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $incident->assigned_operator_name }}</span>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                                Unassigned
                                            </span>
                                        @endif
                                        <a href="{{ route('incidents.show', $incident->id) }}"
                                            class="bg-error-500 shadow-theme-xs hover:bg-error-600 inline-flex rounded-full px-3 py-1 text-xs font-medium text-white transition">
                                            Action
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-success-500 flex min-h-[200px] flex-col items-center justify-center gap-3">
                            <svg class="h-12 w-12 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9Z" />
                            </svg>
                            <div class="text-center">
                                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">System Stable</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No unresolved critical incidents at
                                    this time.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Severity Chart --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="h-full rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h4 class="mb-6 text-base font-semibold text-gray-800 dark:text-white/90">Severity Distribution</h4>
                <div id="severityChart" class="mx-auto flex justify-center"></div>
            </div>
        </div>

        {{-- ─── STATUS OVERVIEW (full row) ──────────────────────────────── --}}
        <div class="col-span-12">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h4 class="mb-2 text-base font-semibold text-gray-800 dark:text-white/90">Status Overview</h4>
                <div class="w-full overflow-hidden">
                    <div id="statusChart" class="w-full"></div>
                </div>
            </div>
        </div>

        {{-- ─── RECENT INCIDENTS TABLE (full row) ───────────────────────── --}}
        <div class="col-span-12">
            <div
                class="flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Incidents</h4>
                    <a href="{{ route('incidents.index') }}"
                        class="text-brand-500 hover:text-brand-600 text-sm font-medium transition">
                        View All
                    </a>
                </div>
                <div class="p-6">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        ID</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Incident</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Severity</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Status</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Assignee</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($recentIncidents as $incident)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                        <td class="px-4 py-3.5">
                                            <span
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">#{{ $incident->id }}</span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <a href="{{ route('incidents.show', $incident->id) }}"
                                                class="text-brand-500 hover:text-brand-600 text-sm font-medium transition">
                                                {{ \Illuminate\Support\Str::limit($incident->title, 60) }}
                                            </a>
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $incident->category_name ?? 'N/A' }}
                                                &bull;
                                                {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}
                                            </p>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @php
                                                $sevClass = match ($incident->severity) {
                                                    'CRITICAL'
                                                        => 'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400',
                                                    'HIGH'
                                                        => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                                                    'MEDIUM'
                                                        => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                                                    'LOW'
                                                        => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                                                    default
                                                        => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $sevClass }} inline-flex rounded-full px-2.5 py-1 text-xs font-medium">
                                                {{ $incident->severity }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @php
                                                $statusClass = match ($incident->status) {
                                                    'OPEN'
                                                        => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400',
                                                    'IN_PROGRESS'
                                                        => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                                                    'RESOLVED'
                                                        => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                                                    'CLOSED'
                                                        => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                    default
                                                        => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $statusClass }} inline-flex rounded-full px-2.5 py-1 text-xs font-medium">
                                                {{ str_replace('_', ' ', $incident->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $incident->assigned_operator_name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                        {{ $recentIncidents->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── RECENT AUDIT LOGS ───────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Audit Activity</h4>
                    <a href="{{ route('audit-logs.index') }}"
                        class="text-brand-500 hover:text-brand-600 text-sm font-medium transition">
                        View All Logs
                    </a>
                </div>
                <div class="p-6">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Timestamp</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        User</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Action</th>
                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Table / Resource</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($recentAuditLogs as $log)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                        <td class="px-4 py-3.5">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $log->user_name ?? 'System' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @php
                                                $actionClass = match (strtoupper($log->action)) {
                                                    'CREATE'
                                                        => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                                                    'UPDATE'
                                                        => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                                                    'DELETE'
                                                        => 'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400',
                                                    default
                                                        => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $actionClass }} inline-flex rounded-full px-2.5 py-1 text-xs font-medium uppercase">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span
                                                class="font-mono text-sm text-gray-700 dark:text-gray-300">{{ $log->table_name }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                            No recent audit logs found.
                                        </td>
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
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Severity Donut Chart ────────────────────────────────── */
            const severityData = {
                CRITICAL: {{ $severityChart['CRITICAL'] ?? 0 }},
                HIGH: {{ $severityChart['HIGH'] ?? 0 }},
                MEDIUM: {{ $severityChart['MEDIUM'] ?? 0 }},
                LOW: {{ $severityChart['LOW'] ?? 0 }}
            };

            new ApexCharts(document.getElementById('severityChart'), {
                series: [severityData.CRITICAL, severityData.HIGH, severityData.MEDIUM, severityData.LOW],
                chart: {
                    type: 'donut',
                    width: 340,
                    fontFamily: 'Satoshi, sans-serif',
                    background: 'transparent'
                },
                colors: ['#F04438', '#F97316', '#F59E0B', '#12B76A'],
                labels: ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW'],
                legend: {
                    show: true,
                    position: 'bottom',
                    fontFamily: 'Satoshi, sans-serif'
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
                stroke: {
                    width: 0
                },
                responsive: [{
                    breakpoint: 640,
                    options: {
                        chart: {
                            width: 280
                        }
                    }
                }]
            }).render();

            /* ── Status Bar Chart ────────────────────────────────────── */
            const statusData = {
                OPEN: {{ $statusChart['OPEN'] ?? 0 }},
                IN_PROGRESS: {{ $statusChart['IN_PROGRESS'] ?? 0 }},
                RESOLVED: {{ $statusChart['RESOLVED'] ?? 0 }},
                CLOSED: {{ $statusChart['CLOSED'] ?? 0 }}
            };

            new ApexCharts(document.getElementById('statusChart'), {
                series: [{
                    name: 'Incidents',
                    data: [
                        statusData.OPEN,
                        statusData.IN_PROGRESS,
                        statusData.RESOLVED,
                        statusData.CLOSED
                    ]
                }],

                chart: {
                    type: 'bar',
                    height: 320,
                    width: '100%',

                    toolbar: {
                        show: false
                    },

                    fontFamily: 'Satoshi, sans-serif',

                    background: 'transparent',

                    parentHeightOffset: 0
                },

                colors: ['#465FFF', '#F59E0B', '#12B76A', '#98A2B3'],

                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 6,
                        columnWidth: '55%',
                        distributed: true
                    }
                },

                dataLabels: {
                    enabled: true,

                    offsetY: -8,

                    style: {
                        fontSize: '12px',
                        fontFamily: 'Satoshi, sans-serif',
                        fontWeight: '700',
                        colors: ['#465FFF', '#F59E0B', '#12B76A', '#98A2B3']
                    }
                },

                legend: {
                    show: false
                },

                grid: {
                    borderColor: '#F2F4F7',
                    strokeDashArray: 4,
                    padding: {
                        top: 10,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                },

                xaxis: {
                    categories: ['OPEN', 'IN PROGRESS', 'RESOLVED', 'CLOSED'],

                    labels: {
                        rotate: 0,

                        style: {
                            colors: ['#465FFF', '#F59E0B', '#12B76A', '#98A2B3'],
                            fontSize: '12px',
                            fontFamily: 'Satoshi, sans-serif',
                            fontWeight: '600'
                        }
                    },

                    axisBorder: {
                        show: false
                    },

                    axisTicks: {
                        show: false
                    }
                },

                yaxis: {
                    min: 0,

                    forceNiceScale: true,

                    labels: {
                        style: {
                            colors: '#98A2B3',
                            fontFamily: 'Satoshi, sans-serif'
                        }
                    }
                },

                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 280
                        },

                        plotOptions: {
                            bar: {
                                columnWidth: '40%'
                            }
                        },

                        xaxis: {
                            labels: {
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        }
                    }
                }]
            }).render();
        });
    </script>
@endpush
