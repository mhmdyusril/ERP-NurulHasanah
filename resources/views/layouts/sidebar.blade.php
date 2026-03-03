<!-- Mobile Backdrop -->
<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 md:hidden">
</div>

<!-- Sidebar -->
<aside 
    x-show="sidebarOpen || window.innerWidth >= 768"
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 w-[280px] bg-white border-r border-gray-100 flex flex-col h-screen md:sticky md:top-0 z-50 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    @resize.window="if (window.innerWidth >= 768) sidebarOpen = false">
    
    <!-- Header/Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-gray-50 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-sage to-emerald-800 rounded-xl flex items-center justify-center text-white shadow-lg shadow-sage/30 group-hover:scale-105 transition-transform duration-300">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg text-gray-900 tracking-tight leading-none group-hover:text-sage transition-colors">ERP RA</span>
                <span class="text-[10px] font-medium text-sage uppercase tracking-widest mt-1">Nurul Hasanah</span>
            </div>
        </a>
        <!-- Close button mobile -->
        <button @click="sidebarOpen = false" class="ml-auto md:hidden p-2 text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto py-8 px-4 space-y-1 scrollbar-hide">
        
        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('dashboard'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-sage' : 'text-gray-400 group-hover:text-gray-700' }} transition-colors"></i>
            Dashboard
        </a>
        
        <div class="pt-6 pb-2 px-4 flex items-center gap-2">
            <span class="h-px w-4 bg-gray-200"></span>
            <span class="text-xs font-bold text-gray-400 tracking-widest uppercase">Modul Utama</span>
            <span class="h-px bg-gray-200 flex-1"></span>
        </div>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('teachers.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('teachers.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('teachers.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="user-cog" class="w-5 h-5 {{ request()->routeIs('teachers.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Manajemen Guru
        </a>

        <a href="{{ route('students.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('students.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('students.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('students.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Data Kesiswaan
        </a>

        <a href="{{ route('classes.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('classes.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('classes.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="building" class="w-5 h-5 {{ request()->routeIs('classes.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Manajemen Kelas
        </a>

        <a href="{{ route('payments.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('payments.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('payments.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="wallet" class="w-5 h-5 {{ request()->routeIs('payments.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Kasir SPP & Tagihan
        </a>

        <a href="{{ route('reports.finance') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('reports.finance') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('reports.finance'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="files" class="w-5 h-5 {{ request()->routeIs('reports.finance') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Pusat Rekap Laporan
        </a>

        <a href="{{ route('carousel.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('carousel.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('carousel.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="image" class="w-5 h-5 {{ request()->routeIs('carousel.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Manajemen Carousel
        </a>
        @endif

        <a href="{{ route('academics.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('academics.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('academics.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="book-open" class="w-5 h-5 {{ request()->routeIs('academics.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Akademik & Nilai
        </a>

        <a href="{{ route('attendances.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('attendances.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('attendances.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="calendar-check" class="w-5 h-5 {{ request()->routeIs('attendances.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Absensi Harian
        </a>

        <a href="{{ route('savings.index') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('savings.*') ? 'bg-sage/10 text-sage font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
            @if(request()->routeIs('savings.*'))
            <span class="absolute left-0 inset-y-2 w-1.5 bg-sage rounded-r-md"></span>
            @endif
            <i data-lucide="piggy-bank" class="w-5 h-5 {{ request()->routeIs('savings.*') ? 'text-sage' : 'text-gray-400 group-hover:text-sage' }} transition-colors"></i>
            Tabungan Siswa
        </a>
    </div>

    <!-- User Profile Area (Bottom) -->
    <div class="p-4 border-t border-gray-50 bg-gray-50/50 m-4 rounded-2xl relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-sage/5 to-softTeal/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex items-center justify-between relative z-10 w-full">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-sage font-bold shadow-sm shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col truncate">
                    <span class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-500 capitalize flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                        {{ Auth::user()->role ?? 'Admin' }}
                    </span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="shrink-0 ml-2">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors tooltip" title="Logout">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
