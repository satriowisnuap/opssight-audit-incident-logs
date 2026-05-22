@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- ─── HEADER ─────────────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                        Profile Settings
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your account information and password.
                    </p>
                </div>
            </div>
        </div>

        {{-- ─── FLASH MESSAGES ─────────────────────────────────────────── --}}
        @if (session('status') === 'profile-updated')
            <div class="col-span-12">
                <div
                    class="border-success-200 bg-success-50 text-success-700 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3 rounded-xl border px-4 py-3 text-sm">
                    <svg class="h-5 w-5 shrink-0 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                    </svg>
                    Profile updated successfully.
                </div>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="col-span-12">
                <div
                    class="border-success-200 bg-success-50 text-success-700 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3 rounded-xl border px-4 py-3 text-sm">
                    <svg class="h-5 w-5 shrink-0 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                    </svg>
                    Password updated successfully.
                </div>
            </div>
        @endif

        {{-- ─── UPDATE PROFILE ─────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div
                class="flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">
                    <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-5 dark:border-gray-800">
                        <div
                            class="bg-brand-100 dark:bg-brand-500/20 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg">
                            <svg class="text-brand-600 dark:text-brand-400 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Profile Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Update your name and email address.</p>
                        </div>
                    </div>

                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── UPDATE PASSWORD ─────────────────────────────────────────── --}}
        <div class="col-span-12">
            <div
                class="flex flex-col rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">
                    <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-5 dark:border-gray-800">
                        <div
                            class="bg-brand-100 dark:bg-brand-500/20 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg">
                            <svg class="text-brand-600 dark:text-brand-400 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Update Password</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ensure your account uses a strong password.
                            </p>
                        </div>
                    </div>

                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
