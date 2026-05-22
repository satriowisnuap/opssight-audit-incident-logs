@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                Edit User
            </h2>
            <a href="{{ route('users.index') }}"
                class="hover:text-brand-500 text-sm font-medium text-gray-500 transition">
                &larr; Back to Users
            </a>
        </div>

        {{-- FLASH MESSAGES --}}
        @if (session('error'))
            <div class="mb-6">
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

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('name')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email Address <span class="text-error-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('email')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Role <span class="text-error-500">*</span>
                        </label>
                        <select id="role" name="role" required {{ auth()->id() == $user->id ? 'disabled' : '' }}
                            class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="ADMIN" {{ old('role', $user->role) === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="OPERATOR" {{ old('role', $user->role) === 'OPERATOR' ? 'selected' : '' }}>Operator</option>
                        </select>
                        @if (auth()->id() == $user->id)
                            <p class="mt-1 text-xs text-gray-500">You cannot change your own role.</p>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                        @error('role')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-5 dark:border-gray-800">
                        <h3 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">Change Password (Optional)</h3>
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            {{-- Password --}}
                            <div>
                                <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    New Password
                                </label>
                                <input type="password" id="password" name="password" minlength="8"
                                    placeholder="Leave blank to keep current"
                                    class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('password')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirm New Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8"
                                    class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                        <a href="{{ route('users.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                            Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
