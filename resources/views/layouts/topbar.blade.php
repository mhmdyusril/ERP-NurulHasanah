<header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 transition-all">
    <!-- Mobile Menu Button & Page Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none md:hidden">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <!-- Page Context (Desktop) -->
        <div class="hidden md:flex items-center gap-2.5 min-w-0">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Portal</span>
            <span class="text-slate-300">/</span>
            <h1 class="text-sm font-bold text-slate-900 tracking-tight truncate max-w-sm">
                @isset($header) {{ trim(strip_tags($header)) }} @else Dashboard @endisset
            </h1>
        </div>

        <div class="w-7 h-7 bg-emerald-800 rounded-lg flex items-center justify-center text-white md:hidden">
            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
        </div>
    </div>

    <!-- Topbar Right Actions -->
    <div class="flex items-center gap-3 ml-auto">
        <!-- AI Assistant Trigger Button (Clean, Non-Intrusive) -->
        <button @click="aiOpen = true" 
                class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors shadow-2xs">
            <span class="text-emerald-700 font-bold">✨</span>
            <span>Tanya Asisten AI</span>
        </button>

        <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

        <!-- Academic Term Pill -->
        <span class="hidden lg:inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
            TA 2026/2027
        </span>

        <!-- User Dropdown -->
        <div class="flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2.5 p-1.5 pr-2.5 rounded-lg hover:bg-slate-100 transition-all focus:outline-none group">
                        <div class="w-7 h-7 rounded-md bg-emerald-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:flex flex-col items-start leading-tight">
                            <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-900 transition-colors">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-slate-500 capitalize font-medium">{{ Auth::user()->role ?? 'Admin' }}</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2.5 border-b border-slate-100">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 py-2 text-xs">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        {{ __('Profil Saya') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" 
                                class="flex items-center gap-2 py-2 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            {{ __('Keluar Sistem') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>
