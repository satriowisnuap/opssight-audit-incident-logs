<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    {{-- Current Password --}}
    <div>
        <label for="update_password_current_password"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ __('Current Password') }}
        </label>
        <input
            id="update_password_current_password"
            name="current_password"
            type="password"
            autocomplete="current-password"
            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400" />
        @if ($errors->updatePassword->get('current_password'))
            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
        @endif
    </div>

    {{-- New Password --}}
    <div>
        <label for="update_password_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ __('New Password') }}
        </label>
        <input
            id="update_password_password"
            name="password"
            type="password"
            autocomplete="new-password"
            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400" />
        @if ($errors->updatePassword->get('password'))
            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
        @endif
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="update_password_password_confirmation"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ __('Confirm Password') }}
        </label>
        <input
            id="update_password_password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400" />
        @if ($errors->updatePassword->get('password_confirmation'))
            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
        @endif
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-4 pt-1">
        <button
            type="submit"
            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
            {{ __('Save Changes') }}
        </button>

        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Saved.') }}
            </p>
        @endif
    </div>
</form>
