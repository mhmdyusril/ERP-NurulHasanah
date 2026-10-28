<!-- Mobile Backdrop -->
<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 md:hidden">
</div>

<!-- Sidebar -->
<aside 
    x-bind:style="sidebarOpen ? 'transform: translateX(0);' : 'transform: translateX(-100%);'"
    style="transform: translateX(-100%);"
    class="fixed inset-y-0 left-0 w-[260px] bg-white border-r border-slate-200 flex flex-col h-screen z-50 transition-transform duration-200 ease-in-out layout-sidebar"
    @resize.window="if (window.innerWidth >= 768) sidebarOpen = false">
    
    <!-- Header/Logo Area -->
    <div class="h-16 flex items-center px-5 border-b border-slate-100 flex-shrink-0 justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 bg-emerald-900 rounded-lg flex items-center justify-center text-white shadow-2xs group-hover:bg-emerald-800 transition-colors">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-sm text-slate-900 tracking-tight leading-none">RA Nurul Hasanah</span>
                <span class="text-[10px] font-medium text-emerald-800 uppercase tracking-wider mt-0.5">Sistem ERP Terpadu</span>
            </div>
        </a>
        <!-- Close button mobile -->
        <button @click="sidebarOpen = false" class="md:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto py-5 px-3 space-y-5">
        
        <!-- Section: Ringkasan -->
        <div>
            <div class="px-2.5 mb-1.5 text-[11px] font-bold text-slate-400 tracking-wider uppercase">
                Ringkasan
            </div>
            <div class="space-y-0.5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Dashboard Utama</span>
                </a>
            </div>
        </div>

        <!-- Section: Kesiswaan & Akademik -->
        <div>
            <div class="px-2.5 mb-1.5 text-[11px] font-bold text-slate-400 tracking-wider uppercase">
                Kesiswaan & Absensi
            </div>
            <div class="space-y-0.5">
                <a href="{{ route('students.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('students.index') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('students.index') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Data Santri</span>
                </a>

                <a href="{{ route('students.cards') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('students.cards') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="contact" class="w-4 h-4 {{ request()->routeIs('students.cards') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Cetak Kartu QR</span>
                </a>

                <a href="{{ route('attendances.scan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('attendances.scan') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="qr-code" class="w-4 h-4 {{ request()->routeIs('attendances.scan') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Scan Presensi QR</span>
                </a>

                <a href="{{ route('attendances.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('attendances.index') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="calendar-check" class="w-4 h-4 {{ request()->routeIs('attendances.index') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Rekap Absensi Harian</span>
                </a>

                <a href="{{ route('academics.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('academics.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="book-open" class="w-4 h-4 {{ request()->routeIs('academics.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Akademik & Nilai</span>
                </a>
            </div>
        </div>

        <!-- Section: Keuangan -->
        <div>
            <div class="px-2.5 mb-1.5 text-[11px] font-bold text-slate-400 tracking-wider uppercase">
                Keuangan & Kas
            </div>
            <div class="space-y-0.5">
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('payments.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('payments.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="wallet" class="w-4 h-4 {{ request()->routeIs('payments.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Kasir SPP & Tagihan</span>
                </a>
                @endif

                <a href="{{ route('savings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('savings.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="piggy-bank" class="w-4 h-4 {{ request()->routeIs('savings.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Tabungan Siswa</span>
                </a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('reports.finance') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('reports.finance') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="files" class="w-4 h-4 {{ request()->routeIs('reports.finance') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Laporan Keuangan</span>
                </a>
                @endif
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        <!-- Section: Pengaturan Sekolah -->
        <div>
            <div class="px-2.5 mb-1.5 text-[11px] font-bold text-slate-400 tracking-wider uppercase">
                Administrasi Sekolah
            </div>
            <div class="space-y-0.5">
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('teachers.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="user-cog" class="w-4 h-4 {{ request()->routeIs('teachers.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Manajemen Guru</span>
                </a>

                <a href="{{ route('classes.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('classes.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="building" class="w-4 h-4 {{ request()->routeIs('classes.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Manajemen Kelas</span>
                </a>

                <a href="{{ route('carousel.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('carousel.*') ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                    <i data-lucide="image" class="w-4 h-4 {{ request()->routeIs('carousel.*') ? 'text-emerald-800' : 'text-slate-400' }}"></i>
                    <span>Carousel Beranda</span>
                </a>
            </div>
        </div>
        @endif

    </div>

    <!-- User Profile Area (Bottom) -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/70">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-8 h-8 rounded-lg bg-emerald-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex flex-col truncate">
                    <span class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-slate-500 capitalize">
                        {{ Auth::user()->role ?? 'Admin' }}
                    </span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
