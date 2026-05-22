@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        Incident #{{ $incident->id ?? '000' }}
                    </h2>

                    @php
                        $statusClass = match ($incident->status ?? 'OPEN') {
                            'OPEN'
                                => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 border-brand-200 dark:border-brand-800',
                            'IN_PROGRESS'
                                => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400 border-warning-200 dark:border-warning-800',
                            'RESOLVED'
                                => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400 border-success-200 dark:border-success-800',
                            'CLOSED'
                                => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                            default
                                => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                        };
                    @endphp

                    <span class="{{ $statusClass }} inline-flex rounded-full border px-3 py-1 text-xs font-semibold">
                        {{ str_replace('_', ' ', $incident->status ?? 'OPEN') }}
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Created on {{ \Carbon\Carbon::parse($incident->created_at ?? now())->format('M d, Y H:i:s') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('incidents.index') }}"
                    class="shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Back
                </a>
                <a href="{{ route('incidents.edit', $incident->id ?? 1) }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Update Incident
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Main Content --}}
            <div class="flex flex-col gap-6 xl:col-span-2">

                {{-- Incident Detail --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $incident->title ?? 'Untitled Incident' }}
                    </h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                        @if (!empty($incident->description))
                            {!! nl2br(e($incident->description)) !!}
                        @else
                            <p class="italic text-gray-400">No description provided.</p>
                        @endif
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Activity Timeline</h3>
                        @php $logCount = count($logs ?? []); @endphp
                        @if ($logCount > 0)
                            <span
                                class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                {{ $logCount }} {{ Str::plural('event', $logCount) }}
                            </span>
                        @endif
                    </div>

                    @forelse($logs ?? [] as $index => $log)
                        @php
                            $action = strtoupper($log->action ?? '');
                            $changes = $log->new_values ? json_decode($log->new_values, true) : [];
                            $notes = $changes['notes'] ?? null;
                            unset($changes['notes']);
                            $isLast = $loop->last;

                            // Determine icon & color based on action
                            $iconConfig = match (true) {
                                str_contains($action, 'CREATE') => [
                                    'bg' => 'bg-success-50 dark:bg-success-500/10',
                                    'border' => 'border-success-200 dark:border-success-500/30',
                                    'icon' => 'text-success-500',
                                    'line' => 'bg-success-200 dark:bg-success-500/20',
                                    'svg' =>
                                        '<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>',
                                ],
                                str_contains($action, 'DELETE') => [
                                    'bg' => 'bg-error-50 dark:bg-error-500/10',
                                    'border' => 'border-error-200 dark:border-error-500/30',
                                    'icon' => 'text-error-500',
                                    'line' => 'bg-error-200 dark:bg-error-500/20',
                                    'svg' =>
                                        '<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>',
                                ],
                                default => [
                                    'bg' => 'bg-brand-50 dark:bg-brand-500/10',
                                    'border' => 'border-brand-200 dark:border-brand-500/30',
                                    'icon' => 'text-brand-500',
                                    'line' => 'bg-brand-200 dark:bg-brand-500/20',
                                    'svg' =>
                                        '<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>',
                                ],
                            };

                            // Human-readable sentence
                            /**
                             * Human-readable sentence
                             */
                            $actor = $log->user_name ?? 'System';
                            $sentences = [];
                            /**
                             * CREATE INCIDENT
                             * Jangan tampilkan detail field
                             */
                            if (str_contains($action, 'CREATE')) {
                                $sentences[] = 'created this incident';
                            } /**
                             * DELETE INCIDENT
                             */ elseif (str_contains($action, 'DELETE')) {
                                $sentences[] = 'deleted this incident';
                            } /**
                             * UPDATE INCIDENT
                             * Baru tampilkan perubahan detail
                             */ else {
                                if (!empty($changes)) {
                                    foreach ($changes as $field => $value) {
                                        /**
                                         * Skip notes
                                         */
                                        if ($field === 'notes') {
                                            continue;
                                        }

                                        $sentences[] = match ($field) {
                                            'status' => 'changed the status to <strong>' .
                                                str_replace('_', ' ', $value) .
                                                '</strong>',
                                            'severity' => 'changed the severity to <strong>' . $value . '</strong>',
                                            'assigned_to' => 'reassigned this incident',
                                            'title' => 'updated the incident title',
                                            'description' => 'updated the description',
                                            'category_id' => 'changed the category',
                                            default => 'updated <strong>' .
                                                ucfirst(str_replace('_', ' ', $field)) .
                                                '</strong>',
                                        };
                                    }
                                }

                                /**
                                 * Fallback
                                 */
                                if (empty($sentences)) {
                                    $sentences[] = 'made an update to this incident';
                                }
                            }

                            $severityConfig = [
                                'CRITICAL' => [
                                    'dot' => 'bg-error-500',
                                    'text' => 'text-error-600 dark:text-error-400',
                                    'bg' => 'bg-error-50 dark:bg-error-500/10',
                                ],
                                'HIGH' => [
                                    'dot' => 'bg-orange-500',
                                    'text' => 'text-orange-600 dark:text-orange-400',
                                    'bg' => 'bg-orange-50 dark:bg-orange-500/10',
                                ],
                                'MEDIUM' => [
                                    'dot' => 'bg-warning-500',
                                    'text' => 'text-warning-600 dark:text-warning-400',
                                    'bg' => 'bg-warning-50 dark:bg-warning-500/10',
                                ],
                                'LOW' => [
                                    'dot' => 'bg-success-500',
                                    'text' => 'text-success-600 dark:text-success-400',
                                    'bg' => 'bg-success-50 dark:bg-success-500/10',
                                ],
                            ];

                            $statusConfig = [
                                'OPEN' => [
                                    'dot' => 'bg-brand-500',
                                    'text' => 'text-brand-600 dark:text-brand-400',
                                    'bg' => 'bg-brand-50 dark:bg-brand-500/10',
                                ],
                                'IN_PROGRESS' => [
                                    'dot' => 'bg-warning-500',
                                    'text' => 'text-warning-600 dark:text-warning-400',
                                    'bg' => 'bg-warning-50 dark:bg-warning-500/10',
                                ],
                                'RESOLVED' => [
                                    'dot' => 'bg-success-500',
                                    'text' => 'text-success-600 dark:text-success-400',
                                    'bg' => 'bg-success-50 dark:bg-success-500/10',
                                ],
                                'CLOSED' => [
                                    'dot' => 'bg-gray-400',
                                    'text' => 'text-gray-600 dark:text-gray-400',
                                    'bg' => 'bg-gray-100 dark:bg-gray-800',
                                ],
                            ];
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

                                {{-- Who + when --}}
                                <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                        {{ $actor }}
                                    </span>
                                    @foreach ($sentences as $si => $sentence)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {!! $sentence !!}@if (!$loop->last)
                                                ,
                                            @endif
                                        </span>
                                    @endforeach
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        · {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y · H:i') }}
                                </p>

                                @if ($notes)
                                    <div
                                        class="mt-3 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.02]">
                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                            Notes
                                        </p>
                                        <p class="mt-2 whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">
                                            {{ $notes }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Change pills (only for meaningful fields) --}}
                                @if (!empty($changes))
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($changes as $field => $value)
                                            @if ($field === 'severity' && isset($severityConfig[$value]))
                                                @php $cfg = $severityConfig[$value]; @endphp
                                                <span
                                                    class="{{ $cfg['bg'] }} {{ $cfg['text'] }} inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold">
                                                    <span class="{{ $cfg['dot'] }} h-1.5 w-1.5 rounded-full"></span>
                                                    {{ $value }}
                                                </span>
                                            @elseif ($field === 'status' && isset($statusConfig[$value]))
                                                @php $cfg = $statusConfig[$value]; @endphp
                                                <span
                                                    class="{{ $cfg['bg'] }} {{ $cfg['text'] }} inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold">
                                                    <span class="{{ $cfg['dot'] }} h-1.5 w-1.5 rounded-full"></span>
                                                    {{ str_replace('_', ' ', $value) }}
                                                </span>
                                            @elseif ($field === 'assigned_to')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    <svg class="h-3 w-3 fill-gray-400" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Assigned to operator #{{ $value }}
                                                </span>
                                            @elseif ($field === 'incident_date')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    <svg class="h-3 w-3 fill-gray-400"
                                                        viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm10 7H4v5a1 1 0 001 1h10a1 1 0 001-1V9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Incident date:
                                                    {{ \Carbon\Carbon::parse($value)->format('M d, Y H:i') }}
                                                </span>
                                            @elseif (!in_array($field, ['title', 'description', 'category_id', 'incident_date']))
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}: {{ $value }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

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
                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Changes to this incident will
                                        appear here.</p>
                                </div>
                            </div>
                        @endforelse

                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="flex flex-col gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                        <h3 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90">Incident Information</h3>

                        <div class="space-y-4">

                            {{-- Severity --}}
                            <div>
                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Severity</span>
                                @php
                                    $sevClass = match ($incident->severity ?? 'LOW') {
                                        'CRITICAL'
                                            => 'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400',
                                        'HIGH'
                                            => 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                                        'MEDIUM'
                                            => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                                        'LOW'
                                            => 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                    };
                                @endphp
                                <span class="{{ $sevClass }} inline-flex rounded-md px-2.5 py-1 text-sm font-medium">
                                    {{ $incident->severity ?? 'LOW' }}
                                </span>
                            </div>

                            {{-- Category --}}
                            <div>
                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Category</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $incident->category_name ?? 'General' }}
                                </span>
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800" />

                            {{-- Assigned --}}
                            <div>
                                <span class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Assigned
                                    To</span>
                                @if ($incident->operator_name)
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="bg-brand-100 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400 flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold">
                                            {{ substr($incident->operator_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $incident->operator_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $incident->operator_email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-sm font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                        Unassigned
                                    </span>
                                @endif
                            </div>

                            <hr class="border-gray-100 dark:border-gray-800" />

                            {{-- Creator --}}
                            <div>
                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Created By</span>
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 fill-gray-400" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $incident->creator_name ?? 'System User' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
