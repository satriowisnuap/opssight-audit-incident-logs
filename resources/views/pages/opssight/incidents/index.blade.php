@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- ─── HEADER & FILTER BAR ────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        Incident Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Monitor and manage all system and operational incidents.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('incidents.create') }}"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333a.833.833 0 011.666 0v5h5a.833.833 0 010 1.667h-5v5a.833.833 0 01-1.666 0v-5h-5a.833.833 0 010-1.667h5v-5z" />
                        </svg>
                        Create Incident
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <form method="GET" action="{{ route('incidents.index') }}"
                    class="shadow-theme-xs flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Search Input --}}
                    <div class="relative flex-grow sm:min-w-[250px] sm:flex-grow-0">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by ID or title..."
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 fill-gray-400"
                            width="16" height="16"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z" />
                        </svg>
                    </div>

                    {{-- Severity Select --}}
                    <div class="relative min-w-[150px]">
                        <select name="severity"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                            <option value="">All Severities</option>

                            <option value="CRITICAL"
                                {{ request('severity') == 'CRITICAL' ? 'selected' : '' }}>
                                CRITICAL
                            </option>

                            <option value="HIGH"
                                {{ request('severity') == 'HIGH' ? 'selected' : '' }}>
                                HIGH
                            </option>

                            <option value="MEDIUM"
                                {{ request('severity') == 'MEDIUM' ? 'selected' : '' }}>
                                MEDIUM
                            </option>

                            <option value="LOW"
                                {{ request('severity') == 'LOW' ? 'selected' : '' }}>
                                LOW
                            </option>

                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    {{-- Status Select --}}
                    <div class="relative min-w-[150px]">
                        <select name="status"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                            <option value="">All Statuses</option>

                            <option value="OPEN"
                                {{ request('status') == 'OPEN' ? 'selected' : '' }}>
                                OPEN
                            </option>

                            <option value="IN_PROGRESS"
                                {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>
                                IN PROGRESS
                            </option>

                            <option value="RESOLVED"
                                {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>
                                RESOLVED
                            </option>

                            <option value="CLOSED"
                                {{ request('status') == 'CLOSED' ? 'selected' : '' }}>
                                CLOSED
                            </option>

                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    {{-- Category Select --}}
                    <div class="relative min-w-[180px]">
                        <select name="category"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                            <option value="">All Categories</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-400"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                        </svg>
                    </div>

                    <button type="submit"
                        class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Filter
                    </button>

                    @if (request()->anyFilled(['search', 'severity', 'status', 'category']))
                        <a href="{{ route('incidents.index') }}"
                            class="hover:text-brand-500 text-sm text-gray-500 transition">
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- ─── INCIDENTS TABLE ────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div
                class="flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">

                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">

                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        ID
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Incident Details
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Severity
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Status
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Assignee
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Actions
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                                @forelse ($incidents as $incident)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                        <td class="px-4 py-3.5">
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                #{{ $incident->id }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3.5">
                                            <h5 class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ \Illuminate\Support\Str::limit($incident->title, 50) }}
                                            </h5>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $incident->category_name ?? 'N/A' }}
                                                &bull;
                                                {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y H:i') }}
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

                                            @if ($incident->operator_name)
                                                <div class="flex items-center gap-2">

                                                    <div
                                                        class="bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold">
                                                        {{ substr($incident->operator_name, 0, 1) }}
                                                    </div>

                                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $incident->operator_name }}
                                                    </span>

                                                </div>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                                    Unassigned
                                                </span>
                                            @endif

                                        </td>

                                        <td class="px-4 py-3.5 text-right">

                                            <div class="flex items-center justify-end gap-2">

                                                <a href="{{ route('incidents.show', $incident->id) }}"
                                                    title="View"
                                                    class="hover:text-brand-500 p-1 text-gray-400 transition">

                                                    <svg class="h-5 w-5 fill-current"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5ZM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5Zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3Z" />
                                                    </svg>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="py-8 text-center">

                                            <div class="flex flex-col items-center justify-center gap-3">

                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                    <svg class="h-6 w-6 text-gray-400"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>

                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    No incidents found matching your criteria.
                                                </p>

                                            </div>

                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($incidents->hasPages())
                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            {{ $incidents->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
