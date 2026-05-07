<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/82 backdrop-blur-xl border-b border-white/70 shadow-[0_8px_24px_rgba(15,23,42,0.05)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-green-50 shadow-sm border border-green-100">
                            <x-application-logo class="block h-9 w-9 fill-current text-green-700" />
                        </div>

                        <div class="hidden sm:block">
                            <p class="text-lg font-black text-slate-900 leading-tight">
                                Shomobay
                            </p>
                            <p class="text-xs text-slate-500 leading-tight">
                                Neighborhood Bulk Buying
                            </p>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="rounded-xl px-5 py-3 font-bold transition"
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-2xl border border-white/70 bg-white/75 px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-white focus:outline-none transition">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-700 font-bold uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>

                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-white/70 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{'hidden': open, 'inline-flex': ! open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{'hidden': ! open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/60 bg-white/88 backdrop-blur-xl">
        <div class="space-y-1 pb-3 pt-3 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-4 border-t border-white/60">
            <div class="px-4 flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 font-bold uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </span>

                <div>
                    <div class="font-semibold text-base text-slate-800">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-sm text-slate-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>