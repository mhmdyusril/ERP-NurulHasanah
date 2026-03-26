<header class="h-16 bg-white/80 backdrop-blur-xl border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 transition-all duration-300">
    <!-- Mobile Menu Button & Page Title -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 text-gray-500 hover:text-sage hover:bg-sage/10 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2 md:hidden">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        
        <!-- Page Context (Desktop) -->
        <div class="hidden md:flex items-center gap-3 min-w-0">
            <div class="w-1 h-6 bg-sage/30 rounded-full flex-shrink-0"></div>
            <h1 class="text-sm font-bold text-gray-900 tracking-tight truncate max-w-xs">
                @isset($header) {{ strip_tags($header) }} @else Dashboard @endisset
            </h1>
        </div>

        <div class="w-8 h-8 bg-gradient-to-br from-sage to-emerald-800 rounded-lg flex items-center justify-center text-white shadow-md md:hidden">
            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Topbar Right Actions -->
    <div class="flex items-center gap-2 sm:gap-4 ml-auto">
        <!-- User Dropdown -->
        <div class="flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2 group">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sage to-softTeal flex items-center justify-center text-white font-bold text-xs shadow-sm group-hover:shadow-md transition-all">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden sm:flex flex-col items-start leading-tight">
                            <span class="text-xs font-bold text-gray-900 group-hover:text-sage transition-colors">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">Administrator</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-sage transition-colors"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
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
