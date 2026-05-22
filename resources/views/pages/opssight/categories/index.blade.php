@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- ─── HEADER & FILTER BAR ────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        Incident Categories
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage categories used to classify incidents.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('categories.create') }}"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333a.833.833 0 011.666 0v5h5a.833.833 0 010 1.667h-5v5a.833.833 0 01-1.666 0v-5h-5a.833.833 0 010-1.667h5v-5z" />
                        </svg>
                        Create Category
                    </a>
                </div>
            </div>

            {{-- ─── SEARCH BAR ─────────────────────────────────────────── --}}
            <div class="mt-4">
                <form method="GET" action="{{ route('categories.index') }}"
                    class="shadow-theme-xs flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Search Input --}}
                    <div class="relative flex-grow sm:min-w-[280px] sm:flex-grow-0">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by category name..."
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-9 pr-3 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400">

                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 fill-gray-400"
                            width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.167 3.333C5.945 3.333 3.333 5.945 3.333 9.167c0 3.221 2.612 5.833 5.834 5.833 3.221 0 5.833-2.612 5.833-5.833 0-3.222-2.612-5.834-5.833-5.834ZM1.667 9.167C1.667 5.024 5.024 1.667 9.167 1.667c4.142 0 7.5 3.357 7.5 7.5 0 1.98-.77 3.78-2.02 5.12l3.275 3.276a1.25 1.25 0 0 1-1.768 1.768l-3.31-3.311A7.454 7.454 0 0 1 9.167 16.667c-4.143 0-7.5-3.358-7.5-7.5Z" />
                        </svg>
                    </div>

                    <button type="submit"
                        class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Search
                    </button>

                    @if (request()->filled('search'))
                        <a href="{{ route('categories.index') }}"
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

        {{-- ─── CATEGORIES TABLE ────────────────────────────────────────── --}}
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
                                        Category
                                    </th>

                                    <th
                                        class="px-4 pb-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Total Incidents
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

                                @forelse ($categories as $category)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                        {{-- Category Name --}}
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="bg-brand-100 dark:bg-brand-500/20 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg">
                                                    <svg class="text-brand-600 dark:text-brand-400 h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                    {{ $category->name }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Total Incidents --}}
                                        <td class="px-4 py-3.5 text-center">
                                            @if ($category->total_incidents > 0)
                                                <span
                                                    class="bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                                                    {{ $category->total_incidents }}
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
                                                {{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="flex items-center justify-end gap-1">

                                                {{-- Edit --}}
                                                <a href="{{ route('categories.edit', $category->id) }}"
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
                                                    x-on:click="$dispatch('open-modal', 'delete-category-{{ $category->id }}')"
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
                                        <td colspan="4" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    No categories found.
                                                    @if (request()->filled('search'))
                                                        Try a different search term.
                                                    @else
                                                        <a href="{{ route('categories.create') }}"
                                                            class="text-brand-500 hover:underline">Create the first
                                                            one.</a>
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
                    @if ($categories->hasPages())
                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            {{ $categories->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    {{--
        ─── DELETE CONFIRMATION MODALS ───────────────────────────────────────
        PENTING: Modal HARUS di luar tabel dan di luar overflow-x-auto
        agar tidak terpotong oleh stacking context / overflow hidden.
        Loop ulang $categories di sini khusus untuk render semua modal.
    --}}
    @foreach ($categories as $category)
        <x-modal
            name="delete-category-{{ $category->id }}"
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
                        Delete Category
                    </h3>

                    @if ($category->total_incidents > 0)
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Cannot delete
                            <span class="font-medium text-gray-700 dark:text-white/80">
                                {{ $category->name }}
                            </span>
                            because it is currently used by
                            {{ $category->total_incidents }}
                            incident(s).
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            You are about to permanently remove
                            <span class="font-medium text-gray-700 dark:text-white/80">
                                {{ $category->name }}
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
                        x-on:click="$dispatch('close-modal', 'delete-category-{{ $category->id }}')"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </button>

                    @if ($category->total_incidents == 0)
                        <form
                            action="{{ route('categories.destroy', $category->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-error-500 hover:bg-error-600 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                                Delete Category
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </x-modal>
    @endforeach
@endsection
