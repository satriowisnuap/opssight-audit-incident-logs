<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Profile (Desktop) -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <a href="{{ route('profile.edit') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-gray-50">

                    <!-- Avatar Inisial -->
                    @php
                        $nameParts = explode(' ', trim(Auth::user()->name));
                        $initials = strtoupper(
                            count($nameParts) >= 2 ? $nameParts[0][0] . $nameParts[1][0] : $nameParts[0][0],
                        );
                        $role = Auth::user()->role;
                        $roleLabel = match ($role) {
                            'ADMIN' => 'Administrator',
                            'OPERATOR' => 'Operator',
                            default => $role,
                        };
                    @endphp

                    <div
                        class="bg-brand-500 ring-brand-100 group-hover:ring-brand-200 relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold text-white shadow-sm ring-2 transition">
                        {{ $initials }}
                    </div>

                    <!-- Nama & Role -->
                    <div class="flex flex-col text-left leading-tight">
                        <span
                            class="group-hover:text-brand-600 max-w-[140px] truncate text-sm font-semibold text-gray-800 transition">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="text-[11px] font-medium text-gray-400">
                            {{ $roleLabel }}
                        </span>
                    </div>

                    <!-- Chevron -->
                    <svg class="group-hover:text-brand-500 h-4 w-4 text-gray-400 transition" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Profile -->
        <div class="border-t border-gray-200 pb-1 pt-4">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-gray-50">
                @php
                    $nameParts = explode(' ', trim(Auth::user()->name));
                    $initials = strtoupper(
                        count($nameParts) >= 2 ? $nameParts[0][0] . $nameParts[1][0] : $nameParts[0][0],
                    );
                    $roleLabel = match (Auth::user()->role) {
                        'ADMIN' => 'Administrator',
                        'OPERATOR' => 'Operator',
                        default => Auth::user()->role,
                    };
                @endphp

                <div
                    class="bg-brand-500 flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-semibold text-white shadow-sm">
                    {{ $initials }}
                </div>

                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                    <span class="text-[11px] font-medium text-gray-400">{{ $roleLabel }}</span>
                </div>
            </a>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
