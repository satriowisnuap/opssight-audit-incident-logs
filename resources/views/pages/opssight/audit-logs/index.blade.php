@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6"
        x-data="{
            open: false,
            loading: false,
            tableName: '',
            recordId: '',
            exists: false,
            details: null,
        
            openDetails(logId) {
                this.open = true;
                this.loading = true;
                this.details = null;
        
                fetch(`/audit-logs/${logId}/details`)
                    .then(res => res.json())
                    .then(data => {
                        this.tableName = data.table_name;
                        this.recordId = data.record_id;
                        this.exists = data.exists;
                        this.details = data.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    });
            }
        }">


        {{-- ─── HEADER ─────────────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        Activity Log
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Full audit trail of all system actions and changes.
                    </p>
                </div>

                {{-- Total count badge --}}
                <div
                    class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-white/[0.03]">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Total Records</span>
                    <span
                        class="bg-brand-100 text-brand-700 dark:bg-brand-500/20 dark:text-brand-400 rounded-full px-2.5 py-0.5 text-xs font-semibold">
                        {{ $logs->total() }}
                    </span>
                </div>
            </div>

            {{-- ─── FILTER BAR ──────────────────────────────────────────── --}}
            <div class="mt-4">
                <form method="GET" action="{{ route('audit-logs.index') }}"
                    class="shadow-theme-xs flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Search --}}
                    <div class="relative flex-grow sm:min-w-[220px] sm:flex-grow-0">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search user, action, target..."
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 fill-gray-400"
                            width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z" />
                        </svg>
                    </div>

                    {{-- Filter: Action --}}
                    <div class="relative min-w-[160px]">
                        <select name="action"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                            <option value="">All Actions</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $action) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    {{-- Filter: Table / Target --}}
                    <div class="relative min-w-[160px]">
                        <select name="table"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                            <option value="">All Tables</option>
                            @foreach ($tables as $table)
                                <option value="{{ $table }}" {{ request('table') == $table ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', ucwords($table)) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    {{-- Filter: User --}}
                    <div class="relative min-w-[170px]">
                        <select name="user"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    {{-- Filter: Date From --}}
                    <div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                    </div>

                    {{-- Filter: Date To --}}
                    <div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                    </div>

                    <button type="submit"
                        class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Filter
                    </button>

                    @if (request()->anyFilled(['search', 'action', 'table', 'user', 'date_from', 'date_to']))
                        <a href="{{ route('audit-logs.index') }}"
                            class="hover:text-brand-500 text-sm text-gray-500 transition">
                            Clear Filters
                        </a>
                    @endif

                </form>
            </div>
        </div>

        {{-- ─── LOGS TABLE ──────────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div
                class="flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">

                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">

                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">

                                    <th
                                        class="whitespace-nowrap px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Time
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        User
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Action
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Target
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Description
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                                @forelse ($logs as $log)
                                    @php
                                        // ── Action badge colour ───────────────────────────
                                        $actionClass = match (true) {
                                            str_starts_with($log->action, 'CREATE')
                                                => 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                            str_starts_with($log->action, 'UPDATE')
                                                => 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400',
                                            str_starts_with($log->action, 'DELETE')
                                                => 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-400',
                                            default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                        };

                                        // ── Human-readable description ────────────────────
                                        $newVals = $log->new_values ? json_decode($log->new_values, true) : [];
                                        $oldVals = $log->old_values ? json_decode($log->old_values, true) : [];

                                        $description = match ($log->action) {
                                            'CREATE_INCIDENT' => 'Logged a new incident (ID #' . $log->record_id . ').',
                                            'UPDATE_INCIDENT' => isset($newVals['notes']) && $newVals['notes']
                                                ? 'Updated incident #' .
                                                    $log->record_id .
                                                    ': ' .
                                                    \Illuminate\Support\Str::limit($newVals['notes'], 60)
                                                : 'Updated status/severity on incident #' . $log->record_id . '.',
                                            'DELETE_INCIDENT' => 'Deleted incident #' . $log->record_id . '.',
                                            'CREATE_CATEGORY' => 'Created category "' .
                                                ($newVals['name'] ?? '—') .
                                                '".',
                                            'UPDATE_CATEGORY' => 'Renamed category to "' .
                                                ($newVals['name'] ?? '—') .
                                                '".',
                                            'DELETE_CATEGORY' => 'Deleted category "' .
                                                ($oldVals['name'] ?? '—') .
                                                '".',
                                            default => isset($newVals['message'])
                                                ? $newVals['message']
                                                : str_replace('_', ' ', $log->action),
                                        };
                                    @endphp

                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                        {{-- Time --}}
                                        <td class="whitespace-nowrap px-4 py-3.5">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- User --}}
                                        <td class="px-4 py-3.5">
                                            @if ($log->user_name)
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold">
                                                        {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                            {{ $log->user_name }}
                                                        </p>
                                                        @if ($log->ip_address)
                                                            <p class="text-xs text-gray-400">
                                                                {{ $log->ip_address }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                                    System
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td class="px-4 py-3.5">
                                            <span
                                                class="{{ $actionClass }} inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold">
                                                {{ str_replace('_', ' ', $log->action) }}
                                            </span>
                                        </td>

                                        {{-- Target --}}
                                        <td class="px-4 py-3.5">
                                            @php
                                                $targetUrl = match ($log->table_name) {
                                                    'incidents' => route('incidents.show', $log->record_id),
                                                    'incident_categories' => route('categories.edit', $log->record_id),
                                                    default => null,
                                                };
                                            @endphp

                                            @if ($targetUrl)
                                                <a href="{{ $targetUrl }}"
                                                    @click.prevent="openDetails({{ $log->id }})"
                                                    class="group flex flex-col hover:underline">
                                                    <span
                                                        class="text-brand-600 dark:text-brand-400 group-hover:text-brand-700 dark:group-hover:text-brand-300 text-sm font-medium capitalize transition">
                                                        {{ str_replace('_', ' ', $log->table_name) }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        ID #{{ $log->record_id }}
                                                    </span>
                                                </a>
                                            @else
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-medium capitalize text-gray-700 dark:text-gray-300">
                                                        {{ str_replace('_', ' ', $log->table_name) }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        ID #{{ $log->record_id }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Description --}}
                                        <td class="max-w-xs px-4 py-3.5">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $description }}
                                            </p>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    No activity logs found matching your criteria.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($logs->hasPages())
                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            {{ $logs->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ─── DETAIL MODAL ────────────────────────────────────────────── --}}

        <template x-teleport="body">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6"
                style="display: none;">

                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm dark:bg-gray-900/80"
                    @click="open = false">
                </div>

                <!-- Modal Panel -->
                <div
                    class="relative z-10 w-full max-w-2xl transform overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl transition-all dark:border-gray-800 dark:bg-gray-900"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.stop>

                    <!-- Header -->
                    <div
                        class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-800/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 flex h-10 w-10 items-center justify-center rounded-xl">
                                <!-- Incident icon -->
                                <template x-if="tableName === 'incidents'">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </template>
                                <!-- Category icon -->
                                <template x-if="tableName === 'incident_categories'">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </template>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white"
                                    x-text="tableName === 'incidents' ? 'Incident Details' : 'Category Details'"></h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="capitalize" x-text="tableName.replace('_', ' ')"></span> Record ID:
                                    #<span x-text="recordId"></span>
                                </p>
                            </div>
                        </div>
                        <button @click="open = false"
                            class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body / Loading -->
                    <div class="px-6 py-6" x-show="loading">
                        <div class="flex flex-col items-center justify-center gap-3 py-12">
                            <div
                                class="border-brand-200 border-t-brand-600 dark:border-brand-900/55 dark:border-t-brand-400 h-10 w-10 animate-spin rounded-full border-4">
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Loading details...</p>
                        </div>
                    </div>

                    <!-- Body / Loaded Details -->
                    <div class="max-h-[70vh] overflow-y-auto px-6 py-6" x-show="!loading && details">

                        <!-- Alert if deleted -->
                        <template x-if="details && details.is_deleted">
                            <div
                                class="mb-5 flex gap-3 rounded-xl border border-red-100 bg-red-50/50 p-4 dark:border-red-500/10 dark:bg-red-500/5">
                                <svg class="h-5 w-5 shrink-0 text-red-600 dark:text-red-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-red-850 text-sm font-semibold dark:text-red-400">Record Has Been
                                        Deleted</h4>
                                    <p class="mt-1 text-xs text-red-700 dark:text-red-500/90">This item no longer exists in
                                        the active database. Displaying final logged state from the audit archives.</p>
                                </div>
                            </div>
                        </template>

                        <!-- INCIDENT DISPLAY -->
                        <template x-if="tableName === 'incidents' && details">
                            <div class="flex flex-col gap-6">
                                <!-- Title & Description -->
                                <div>
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Title</span>
                                    <h4 class="mt-1 text-base font-semibold text-gray-800 dark:text-white"
                                        x-text="details.title"></h4>
                                </div>

                                <div>
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Description</span>
                                    <div class="mt-1 whitespace-pre-line rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-700 dark:border-gray-800/80 dark:bg-gray-800/40 dark:text-gray-300"
                                        x-text="details.description"></div>
                                </div>

                                <!-- Meta Fields Grid -->
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Severity</span>
                                        <div class="mt-2.5">
                                            <span
                                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold tracking-wide"
                                                :class="{
                                                    'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400': details
                                                        .severity === 'CRITICAL',
                                                    'bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400': details
                                                        .severity === 'HIGH',
                                                    'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400': details
                                                        .severity === 'MEDIUM',
                                                    'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': details
                                                        .severity === 'LOW',
                                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400': ![
                                                        'CRITICAL', 'HIGH', 'MEDIUM', 'LOW'
                                                    ].includes(details.severity)
                                                }"
                                                x-text="details.severity"></span>
                                        </div>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Status</span>
                                        <div class="mt-2.5">
                                            <span
                                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold tracking-wide"
                                                :class="{
                                                    'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400': details
                                                        .status === 'OPEN',
                                                    'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400': details
                                                        .status === 'IN_PROGRESS',
                                                    'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': details
                                                        .status === 'RESOLVED',
                                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400': details
                                                        .status === 'CLOSED'
                                                }"
                                                x-text="details.status.replace('_', ' ')"></span>
                                        </div>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Category</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.category_name || 'Uncategorized'"></p>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Incident
                                            Date</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.incident_date ? new Date(details.incident_date).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) : 'N/A'">
                                        </p>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Reported
                                            By</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.creator_name || 'System / Automated'"></p>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800/80 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Assigned
                                            To</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.operator_name || 'Unassigned'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- CATEGORY DISPLAY -->
                        <template x-if="tableName === 'incident_categories' && details">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <span
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Category
                                        Name</span>
                                    <h4 class="mt-1 text-lg font-bold text-gray-800 dark:text-white"
                                        x-text="details.name"></h4>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Created
                                            At</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.created_at ? new Date(details.created_at).toLocaleString() : 'N/A'">
                                        </p>
                                    </div>

                                    <div
                                        class="rounded-xl border border-gray-100 bg-gray-50/20 p-4 dark:border-gray-800 dark:bg-gray-800/10">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Last Updated
                                            At</span>
                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            x-text="details.updated_at ? new Date(details.updated_at).toLocaleString() : 'N/A'">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Audit Log Values Diff -->
                        <template x-if="details && (details.old_values || details.new_values)">
                            <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-800">
                                <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">
                                    Change History
                                </h4>
                                <div class="space-y-4">
                                    <template
                                        x-for="(value, key) in details.old_values"
                                        x-show="key !== 'created_at' && key !== 'updated_at'"
                                        :key="key">
                                        <div
                                            class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/30">
                                            <!-- Field Name -->
                                            <p class="text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500"
                                                x-text="key.replace('_', ' ')">
                                            </p>
                                            <!-- DELETE CASE -->
                                            <template
                                                x-if="!details.new_values || Object.keys(details.new_values).length === 0">
                                                <div class="mt-2">
                                                    <p class="text-xs text-gray-400">
                                                        Previous Value
                                                    </p>
                                                    <p class="text-sm font-medium text-red-600 dark:text-red-400"
                                                        x-text="value">
                                                    </p>
                                                    <p class="mt-3 text-xs italic text-gray-400">
                                                        This value was removed because the record was deleted.
                                                    </p>
                                                </div>
                                            </template>

                                            <!-- UPDATE CASE -->
                                            <template x-if="details.new_values && details.new_values[key]">
                                                <div class="mt-2 space-y-3">
                                                    <div>
                                                        <p class="text-xs text-gray-400">
                                                            Previous Value
                                                        </p>
                                                        <p class="text-sm font-medium text-red-600 dark:text-red-400"
                                                            x-text="value">
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-400">
                                                            New Value
                                                        </p>
                                                        <p class="text-sm font-medium text-green-600 dark:text-green-400"
                                                            x-text="details.new_values[key]">
                                                        </p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Footer -->
                    <div
                        class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-800/30">
                        <button @click="open = false"
                            class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </template>

    </div>
@endsection
