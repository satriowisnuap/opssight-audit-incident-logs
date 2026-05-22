@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white/90">
                Create User
            </h2>
            <a href="{{ route('users.index') }}"
                class="hover:text-brand-500 text-sm font-medium text-gray-500 transition">
                &larr; Back to Users
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
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
                        <select id="role" name="role" required
                            class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Select Role</option>
                            <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="OPERATOR" {{ old('role') === 'OPERATOR' ? 'selected' : '' }}>Operator</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Password <span class="text-error-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required minlength="8"
                                class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @error('password')
                                <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirm Password <span class="text-error-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                                class="focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                        <a href="{{ route('users.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
