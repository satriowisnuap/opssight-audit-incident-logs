@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $user->name }}
                    </h2>

                    @if ($user->role === 'ADMIN')
                        <span class="bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 inline-flex items-center rounded-full border border-brand-200 px-3 py-1 text-xs font-semibold dark:border-brand-800">
                            ADMIN
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs font-semibold dark:border-gray-700">
                            OPERATOR
                        </span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $user->email }} · Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('users.index') }}"
                    class="shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Back
                </a>
                <a href="{{ route('users.edit', $user->id) }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit User
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Main Content --}}
            <div class="flex flex-col gap-6 xl:col-span-2">

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    {{-- Total Assigned --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Assigned</p>
                                <h4 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $totalAssigned }}</h4>
                            </div>
                            <div class="bg-brand-50 dark:bg-brand-500/10 flex h-10 w-10 items-center justify-center rounded-full">
                                <svg class="text-brand-500 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Open Incidents --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Incidents</p>
                                <h4 class="mt-1 text-2xl font-bold text-warning-600 dark:text-warning-400">{{ $openIncidents }}</h4>
                            </div>
                            <div class="bg-warning-50 dark:bg-warning-500/10 flex h-10 w-10 items-center justify-center rounded-full">
                                <svg class="text-warning-500 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Resolved Incidents --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Resolved Incidents</p>
                                <h4 class="mt-1 text-2xl font-bold text-success-600 dark:text-success-400">{{ $resolvedIncidents }}</h4>
                            </div>
                            <div class="bg-success-50 dark:bg-success-500/10 flex h-10 w-10 items-center justify-center rounded-full">
                                <svg class="text-success-500 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Activity</h3>
                        @php $logCount = count($logs ?? []); @endphp
                        @if ($logCount > 0)
                            <span
                                class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                Last {{ $logCount }} {{ Str::plural('event', $logCount) }}
                            </span>
                        @endif
                    </div>

                    @forelse($logs ?? [] as $index => $log)
                        @php
                            $action = strtoupper($log->action ?? '');
                            $isLast = $loop->last;

                            // Determine icon & color based on action
                            $iconConfig = match (true) {
                                str_contains($action, 'CREATE') => [
                                    'bg' => 'bg-success-50 dark:bg-success-500/10',
                                    'border' => 'border-success-200 dark:border-success-500/30',
                                    'icon' => 'text-success-500',
                                    'line' => 'bg-success-200 dark:bg-success-500/20',
                                    'svg' => '<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>',
                                ],
                                str_contains($action, 'DELETE') => [
                                    'bg' => 'bg-error-50 dark:bg-error-500/10',
                                    'border' => 'border-error-200 dark:border-error-500/30',
                                    'icon' => 'text-error-500',
                                    'line' => 'bg-error-200 dark:bg-error-500/20',
                                    'svg' => '<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>',
                                ],
                                default => [
                                    'bg' => 'bg-brand-50 dark:bg-brand-500/10',
                                    'border' => 'border-brand-200 dark:border-brand-500/30',
                                    'icon' => 'text-brand-500',
                                    'line' => 'bg-brand-200 dark:bg-brand-500/20',
                                    'svg' => '<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>',
                                ],
                            };
                        @endphp

                        <div class="flex gap-4">

                            {{-- Icon column --}}
                            <div class="flex flex-col items-center">
                                <div
                                    class="{{ $iconConfig['bg'] }} {{ $iconConfig['border'] }} {{ $iconConfig['icon'] }} flex h-9 w-9 shrink-0 items-center justify-center rounded-full border">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        {!! $iconConfig['svg'] !!}
                                    </svg>
                                </div>
                                @if (!$isLast)
                                    <div class="{{ $iconConfig['line'] }} mt-1 w-px flex-1"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="{{ $isLast ? 'pb-0' : 'pb-7' }} min-w-0 flex-1">

                                <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                        {{ str_replace('_', ' ', $action) }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        on {{ $log->table_name }}
                                        @if($log->record_id) #{{ $log->record_id }} @endif
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        · {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y · H:i') }}
                                </p>

                            </div>
                        </div>

                    @empty
                        <div class="flex flex-col items-center justify-center gap-3 py-12 text-center">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                <svg class="h-6 w-6 fill-gray-400" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No activity yet</p>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">User's actions will appear here.</p>
                            </div>
                        </div>
                    @endforelse

                </div>

            </div>

            {{-- Sidebar --}}
            <div class="flex flex-col gap-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90">User Information</h3>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Full Name</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $user->name }}
                            </span>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-800" />

                        {{-- Email --}}
                        <div>
                            <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Email Address</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $user->email }}
                            </span>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-800" />

                        {{-- Role --}}
                        <div>
                            <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Role</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ ucfirst(strtolower($user->role)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
