<header class="h-24 bg-white/80 backdrop-blur-xl border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 transition-all duration-300">
    <!-- Mobile Menu Button & Brand -->
    <div class="flex items-center md:hidden gap-3">
        <button class="p-2 -ml-2 text-gray-500 hover:text-sage hover:bg-sage/10 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="w-8 h-8 bg-gradient-to-br from-sage to-emerald-800 rounded-lg flex items-center justify-center text-white shadow-md">
            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Search Bar (Centered on Desktop) -->
    <div class="hidden md:flex flex-1 max-w-2xl mx-auto px-4">
        <div class="relative w-full group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
            </div>
            <input type="text" 
                class="block w-full pl-11 pr-4 py-3 bg-gray-50/80 border-transparent hover:bg-gray-100/80 text-gray-900 rounded-2xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm placeholder-gray-400 sm:text-sm" 
                placeholder="Cari siswa (NIS/Nama), invoice SPP, atau kelas...">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none hidden sm:flex">
                <span class="text-xs font-semibold text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 shadow-sm">Ctrl K</span>
            </div>
        </div>
    </div>

    <!-- Topbar Right Actions -->
    <div class="flex items-center gap-2 sm:gap-4 ml-auto pl-4 border-l border-gray-100 h-10">
        <!-- Notifications -->
        <button class="relative p-2 text-gray-400 hover:text-sage transition-colors rounded-xl hover:bg-sage/10 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span class="absolute top-2 right-2 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500 ring-2 ring-white"></span>
            </span>
        </button>

        <!-- Settings/Help -->
        <button class="hidden sm:flex relative p-2 text-gray-400 hover:text-sage transition-colors rounded-xl hover:bg-sage/10 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
            <i data-lucide="help-circle" class="w-5 h-5"></i>
        </button>

        <!-- User Dropdown (Mobile) -->
        <div class="md:hidden">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center p-1 rounded-full hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sage to-softTeal flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 py-2.5">
                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        {{ __('Profil Saya') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                class="flex items-center gap-2 py-2.5 text-red-600 hover:text-red-700 hover:bg-red-50">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            {{ __('Keluar Sistem') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>
