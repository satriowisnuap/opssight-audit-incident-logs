{{-- ─── DELETE ACCOUNT ─────────────────────────────────────────── --}}
<div class="col-span-12">
    <div class="flex flex-col rounded-2xl border border-red-200 bg-white dark:border-red-500/20 dark:bg-white/[0.03]">
        <div class="p-6">
            <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-5 dark:border-gray-800">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-500/20">
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ __('Delete Account') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Permanently delete your account and all its data.') }}
                    </p>
                </div>
            </div>

            <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>

            <button
                type="button"
                x-data=""
                x-on:click="$dispatch('open-modal', 'confirm-user-deletion')"
                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">
                {{ __('Delete Account') }}
            </button>
        </div>
    </div>
</div>

{{-- ─── DELETE CONFIRMATION MODAL ───────────────────────────────── --}}
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <div class="p-6">

        {{-- Icon --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 dark:bg-red-500/10">
            <svg class="h-7 w-7 fill-red-600 dark:fill-red-400" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-6a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" />
            </svg>
        </div>

        {{-- Title & Message --}}
        <div class="mt-5 text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('Delete Account') }}
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>
        </div>

        {{-- Form --}}
        <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
            @csrf
            @method('delete')

            <div>
                <label for="delete_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Password') }}
                </label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Enter your password') }}"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-700 outline-none transition dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-400" />
                @if ($errors->userDeletion->get('password'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex items-center justify-center gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close-modal', 'confirm-user-deletion')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>

    </div>
</x-modal>
