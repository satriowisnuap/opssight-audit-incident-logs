@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                    Edit Category
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update the details for <span
                        class="font-medium text-gray-700 dark:text-white/80">{{ $category->name }}</span>.
                </p>
            </div>

            <a href="{{ route('categories.index') }}"
                class="shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" />
                </svg>
                Back to List
            </a>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8">
                    <div class="flex flex-col gap-6">

                        {{-- Category Name --}}
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Category Name
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                value="{{ old('name', $category->name) }}"
                                placeholder="E.g., Network Outage, Security Breach..."
                                class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 @error('name') border-error-500 @enderror w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('name')
                                <p class="text-error-500 mt-1.5 flex items-center gap-1 text-xs">
                                    <svg class="h-3.5 w-3.5 shrink-0 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Meta info --}}
                        <div
                            class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">
                                Category Info
                            </p>
                            <dl class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                                    <dd class="font-medium text-gray-700 dark:text-gray-300">#{{ $category->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                                    <dd class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="rounded-b-2xl border-t border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-900/50">
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" onclick="window.history.back()"
                            class="shadow-theme-xs rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                            Save Changes
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@endsection
