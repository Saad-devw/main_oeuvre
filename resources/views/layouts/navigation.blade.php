<aside x-data="{ open: false }"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 text-white"
    style="background: linear-gradient(180deg, #1a6b3a 0%, #145a30 55%, #0f4526 100%);">

    {{-- Logo --}}
    <div class="shrink-0 h-20 flex items-center px-5 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <span class="w-10 h-10 rounded-xl bg-white flex items-center justify-center flex-shrink-0 shadow-md shadow-black/10"
                style="color:#1a6b3a;">
                <i class="fa fa-users"></i>
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold leading-tight text-white truncate">Personnel Occasionnel</span>
                <span class="block text-[11px] text-green-100/60 truncate">Commune de Foum El Anceur</span>
            </span>
        </a>
    </div>

    {{-- Navigation links --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-1">

        <p class="px-3 mb-2 text-[10px] font-semibold tracking-widest uppercase text-green-100/40">Menu</p>

        {{-- Dashboard --}}
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot>
            {{ __('Tableau de bord') }}
        </x-sidebar-link>

        {{-- Ouvriers --}}
        <x-sidebar-link :href="route('ouvriers.index')" :active="request()->routeIs('ouvriers.*')">
            <x-slot name="icon">
                <i class="fa fa-users text-[15px]"></i>
            </x-slot>
            {{ __('Ouvriers saisonniers') }}
        </x-sidebar-link>

        {{-- Documents --}}
        <x-sidebar-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
            <x-slot name="icon">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </x-slot>
            {{ __('Documents du mois') }}
        </x-sidebar-link>

    </nav>

    {{-- User section --}}
    <div class="shrink-0 px-3 py-3 border-t border-white/10 relative"
        x-data="{ menu: false }"
        @click.outside="menu = false"
        @keydown.escape.window="menu = false">

        {{-- Pop-up menu (opens above the button) --}}
        <div x-show="menu" x-transition.opacity.duration.150ms style="display: none;"
            class="absolute bottom-full left-3 right-3 mb-2 bg-white text-gray-700 rounded-xl shadow-xl ring-1 ring-black/5 py-1.5 z-50">

            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ __('Profil') }}
            </a>

            <div class="my-1 border-t border-gray-100"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Se déconnecter') }}
                </button>
            </form>
        </div>

        {{-- Trigger --}}
        <button type="button" @click="menu = !menu"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 transition-colors">
            <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-sm font-bold flex-shrink-0"
                style="color:#1a6b3a;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 text-left min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-green-100/60 truncate">{{ Auth::user()->email }}</p>
            </div>
            <svg class="w-4 h-4 text-green-100/60 flex-shrink-0 transition-transform" :class="menu ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    {{-- Mobile overlay toggle --}}
    <div class="sm:hidden absolute top-4 -right-10">
        <button @click="open = !open"
            class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

</aside>
