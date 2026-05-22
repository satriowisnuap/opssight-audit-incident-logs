@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- ─── HEADER & FILTER BAR ────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        User Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage system users and their roles.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('users.create') }}"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333a.833.833 0 011.666 0v5h5a.833.833 0 010 1.667h-5v5a.833.833 0 01-1.666 0v-5h-5a.833.833 0 010-1.667h5v-5z" />
                        </svg>
                        Create User
                    </a>
                </div>
            </div>

            {{-- ─── SEARCH & FILTER BAR ─────────────────────────────────────────── --}}
            <div class="mt-4">
                <form method="GET" action="{{ route('users.index') }}"
                    class="shadow-theme-xs flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Search Input --}}
                    <div class="relative flex-grow sm:min-w-[280px] sm:flex-grow-0">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 fill-gray-400"
                            width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z" />
                        </svg>
                    </div>

                    {{-- Role Filter --}}
                    <div>
                        <select name="role"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-8 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">
                            <option value="">All Roles</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="OPERATOR" {{ request('role') === 'OPERATOR' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Filter
                    </button>

                    @if (request()->filled('search') || request()->filled('role'))
                        <a href="{{ route('users.index') }}"
                            class="hover:text-brand-500 text-sm text-gray-500 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- ─── FLASH MESSAGES ─────────────────────────────────────────── --}}
        @if (session('success'))
            <div class="col-span-12">
                <div
                    class="border-success-200 bg-success-50 text-success-700 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3 rounded-xl border px-4 py-3 text-sm">
                    <svg class="h-5 w-5 shrink-0 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="col-span-12">
                <div
                    class="border-error-200 bg-error-50 text-error-700 dark:border-error-500/20 dark:bg-error-500/10 dark:text-error-400 flex items-center gap-3 rounded-xl border px-4 py-3 text-sm">
                    <svg class="h-5 w-5 shrink-0 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- ─── USERS TABLE ────────────────────────────────────────── --}}
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
                                        User
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Role
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Assigned Incidents
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Created At
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Actions
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                                @forelse ($users as $user)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                        {{-- User Info --}}
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="bg-brand-100 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400 flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-medium text-gray-800 dark:text-white/90">
                                                        {{ $user->name }}
                                                    </span>
                                                    <span class="block text-xs text-gray-500">
                                                        {{ $user->email }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Role --}}
                                        <td class="px-4 py-3.5">
                                            @if ($user->role === 'ADMIN')
                                                <span class="bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 inline-flex items-center rounded-full border border-brand-200 px-2.5 py-1 text-xs font-semibold dark:border-brand-800">
                                                    ADMIN
                                                </span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-xs font-semibold dark:border-gray-700">
                                                    OPERATOR
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Assigned Incidents --}}
                                        <td class="px-4 py-3.5 text-center">
                                            @if ($user->assigned_incidents > 0)
                                                <span
                                                    class="bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                                                    {{ $user->assigned_incidents }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                                    0
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Created At --}}
                                        <td class="px-4 py-3.5">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                {{-- Show --}}
                                                <a href="{{ route('users.show', $user->id) }}"
                                                    title="View Detail"
                                                    class="hover:text-brand-500 rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                    </svg>
                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    title="Edit"
                                                    class="hover:text-brand-500 rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                                                    </svg>
                                                </a>

                                                {{-- Delete --}}
                                                <button
                                                    type="button"
                                                    x-data=""
                                                    x-on:click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                                    title="Delete"
                                                    class="hover:bg-error-50 hover:text-error-500 dark:hover:bg-error-500/10 dark:hover:text-error-400 rounded-lg p-1.5 text-gray-400 transition">
                                                    <svg class="h-5 w-5 fill-current"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" />
                                                    </svg>
                                                </button>
                                            </div>
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
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    No users found.
                                                    @if (request()->filled('search') || request()->filled('role'))
                                                        Try a different filter.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($users->hasPages())
                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            {{ $users->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    {{-- ─── DELETE CONFIRMATION MODALS ─────────────────────────────────────── --}}
    @foreach ($users as $user)
        <x-modal
            name="delete-user-{{ $user->id }}"
            :show="false"
            maxWidth="md">
            <div class="p-6">

                {{-- Icon --}}
                <div
                    class="bg-error-50 dark:bg-error-500/10 mx-auto flex h-14 w-14 items-center justify-center rounded-full">
                    <svg class="fill-error-600 dark:fill-error-400 h-7 w-7"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-6a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" />
                    </svg>
                </div>

                {{-- Title & Message --}}
                <div class="mt-5 text-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Delete User
                    </h3>

                    @php
                        $isSelf = auth()->id() == $user->id;
                        $hasIncidents = DB::table('incidents')->where('reported_by', $user->id)->orWhere('assigned_to', $user->id)->count() > 0;
                    @endphp

                    @if ($isSelf)
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            You cannot delete your own account.
                        </p>
                    @elseif ($hasIncidents)
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Cannot delete
                            <span class="font-medium text-gray-700 dark:text-white/80">
                                {{ $user->name }}
                            </span>
                            because they have related incidents.
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            You are about to permanently remove
                            <span class="font-medium text-gray-700 dark:text-white/80">
                                {{ $user->name }}
                            </span>.
                        </p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                            This action cannot be undone.
                        </p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex items-center justify-center gap-3">

                    {{-- Cancel --}}
                    <button
                        x-on:click="$dispatch('close-modal', 'delete-user-{{ $user->id }}')"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>

                    @if (!$isSelf && !$hasIncidents)
                        <form
                            action="{{ route('users.destroy', $user->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-error-500 hover:bg-error-600 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                                Delete User
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </x-modal>
    @endforeach
@endsection
