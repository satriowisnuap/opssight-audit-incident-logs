@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                    Create New Incident
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Log a new operational or system incident for tracking.
                </p>
            </div>

            <a href="{{ route('incidents.index') }}"
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

            <form action="{{ route('incidents.store') }}" method="POST">
                @csrf

                <div class="p-6 md:p-8">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Title --}}
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Incident Title
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="title" required value="{{ old('title') }}"
                                placeholder="E.g., Database Connection Timeout"
                                class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('title')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Category
                                <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category_id" required
                                    class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-3 pl-4 pr-10 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories ?? [] as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-500"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </div>
                            @error('category_id')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Severity --}}
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Severity
                                <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="severity" required
                                    class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-3 pl-4 pr-10 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="" disabled selected>Select Severity</option>
                                    <option value="CRITICAL" {{ old('severity') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL
                                    </option>
                                    <option value="HIGH" {{ old('severity') == 'HIGH' ? 'selected' : '' }}>HIGH</option>
                                    <option value="MEDIUM" {{ old('severity') == 'MEDIUM' ? 'selected' : '' }}>MEDIUM
                                    </option>
                                    <option value="LOW" {{ old('severity') == 'LOW' ? 'selected' : '' }}>LOW</option>
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-500"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </div>
                            @error('severity')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned Operator (ADMIN ONLY) --}}
                        @if(auth()->check() && auth()->user()->role === 'ADMIN')
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Assigned Operator
                            </label>
                            <div class="relative">
                                <select name="assigned_to"
                                    class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-3 pl-4 pr-10 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="">Unassigned</option>
                                    @foreach ($operators ?? [] as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 fill-gray-500"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </div>
                            @error('assigned_to')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif



                        {{-- Incident Date --}}
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Incident Date
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="datetime-local" name="incident_date" required value="{{ old('incident_date') }}"
                                class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('incident_date')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-span-1 md:col-span-2">
                            <label class="mb-2.5 block text-sm font-medium text-gray-800 dark:text-white/90">
                                Description
                            </label>
                            <textarea name="description" rows="5" placeholder="Describe the incident in detail..."
                                class="focus:border-brand-500 focus:ring-brand-500/20 dark:focus:border-brand-500 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm outline-none transition focus:ring-4 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-error-500 mt-1 text-xs">{{ $message }}</p>
                            @enderror
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
                            Submit Incident
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
