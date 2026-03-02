<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight">
                    {{ __('Dashboard Utama') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Sistem Perencanaan Sumber Daya Perusahaan RA Nurul Hasanah</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-sage transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                    <i data-lucide="printer" class="w-4 h-4 mr-2 text-gray-400"></i>
                    Laporan
                </button>
                <button class="inline-flex items-center justify-center px-4 py-2 bg-sage hover:bg-emerald-800 border border-transparent rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Siswa Baru
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Welcome Banner Container -->
    <div class="relative w-full rounded-[2rem] overflow-hidden mb-8 shadow-sm">
        <!-- Abstract Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-sage to-emerald-900"></div>
        <div class="absolute inset-0 opacity-20">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="circles" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="2" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#circles)"/>
            </svg>
        </div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-10 w-48 h-48 bg-softTeal/20 rounded-full blur-2xl"></div>
        
        <!-- Welcome Content -->
        <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
            <div class="text-white max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-4 text-xs font-semibold tracking-wider uppercase text-white/90">
                    <span class="w-2 h-2 rounded-full bg-softTeal animate-pulse"></span>
                    Tahun Ajaran 2026/2027
                </div>
                <h3 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-sage-50 text-base md:text-lg opacity-90 leading-relaxed">Pantau aktivitas akademik, manajemen pendaftaran siswa, dan kelola keuangan SPP secara instan dari satu layar pintar.</p>
            </div>
            
            <div class="hidden md:flex shrink-0">
                <div class="w-32 h-32 bg-white/10 backdrop-blur-xl rounded-[2rem] border border-white/20 shadow-2xl flex items-center justify-center -rotate-6 hover:rotate-0 transition-transform duration-500">
                    <i data-lucide="backpack" class="w-16 h-16 text-white opacity-90"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-sage/5 to-transparent rounded-bl-[100px] -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-sage/10 rounded-2xl flex items-center justify-center text-sage">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <span class="flex items-center text-sm font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +12%
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Total Siswa Aktif</p>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($totalSiswa ?? 0, 0, ',', '.') }}</h4>
                    <span class="text-sm font-medium text-gray-400">Siswa</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-softTeal/5 to-transparent rounded-bl-[100px] -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-softTeal/20 rounded-2xl flex items-center justify-center text-sage">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <span class="flex items-center text-sm font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                    <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Baru
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Dana SPP Masuk (Bulan Ini)</p>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($totalDanaMasuk ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/40 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group md:col-span-2 lg:col-span-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100 to-transparent rounded-bl-[100px] -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-600">
                    <i data-lucide="contact" class="w-6 h-6"></i>
                </div>
                <span class="text-sm font-semibold text-gray-500">
                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Guru & Staf Aktif</p>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($guruAktif ?? 0, 0, ',', '.') }}</h4>
                    <span class="text-sm font-medium text-gray-400">Pegawai</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Dashboard Interactive Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bar Chart Widget -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 lg:col-span-2 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden">
            <div class="absolute -top-32 -right-32 w-64 h-64 bg-sage/5 rounded-full blur-3xl -z-10"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Grafik Kas SPP</h3>
                    <p class="text-sm text-gray-400 font-medium">Statistik pemasukan 6 bulan terakhir</p>
                </div>
                <!-- Dynamic Filters -->
                <div class="flex bg-gray-50/80 p-1.5 rounded-xl border border-gray-100">
                    <button class="px-4 py-2 text-xs font-bold bg-white text-sage shadow-sm border border-gray-100 rounded-lg transition-all shadow-sage/10">Semester Ini</button>
                    <button class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-900 rounded-lg transition-all">Tahun Lalu</button>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="flex-1 w-full min-h-[320px] relative z-10" id="sppMainChart"></div>
        </div>
        
        <!-- Interactive Timeline Widget -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between relative z-10 bg-white/50 backdrop-blur-sm">
                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Aktivitas Sistem</h3>
                <a href="#" class="text-xs font-bold text-sage hover:text-emerald-800 transition-colors uppercase tracking-wider bg-sage/10 px-3 py-1.5 rounded-lg">Log Lengkap</a>
            </div>
            
            <div class="p-6 flex-1 relative z-10">
                <div class="relative pl-6 space-y-6">
                    <!-- Vertical Track -->
                    <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gradient-to-b from-gray-200 via-gray-100 to-transparent rounded-full"></div>
                    
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="relative">
                            <!-- Bullet Marker -->
                            <div class="absolute -left-6 w-6 h-6 rounded-full {{ $activity['bg'] }} border-4 border-white flex items-center justify-center shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-current {{ $activity['color'] }} shadow-[0_0_8px_currentColor]"></span>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="bg-gray-50/60 p-4 rounded-2xl border border-gray-100 hover:border-sage/30 hover:shadow-md transition-all duration-300 group cursor-default">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <h4 class="text-sm font-bold text-gray-900 group-hover:text-sage transition-colors">{{ $activity['title'] }}</h4>
                                    <span class="text-[10px] uppercase tracking-wider font-bold text-gray-400 shrink-0 bg-white px-2 py-0.5 rounded border border-gray-100">{{ $activity['time'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-10">
                            <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-500 font-medium">Belum ada aktivitas baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>

    <!-- Inject ApexCharts library -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Render Chart Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart configuration
            const chartData = @json($chartData ?? ['labels' => [], 'data' => []]);
            
            const options = {
                series: [{
                    name: 'Total SPP Diterima',
                    data: chartData.data
                }],
                chart: {
                    type: 'area', // Area chart gives a premium gradient look
                    height: '100%',
                    parentHeightOffset: 0,
                    toolbar: { show: false },
                    fontFamily: 'Inter, Poppins, sans-serif',
                    zoom: { enabled: false },
                    dropShadow: {
                        enabled: true,
                        top: 4,
                        left: 0,
                        blur: 4,
                        opacity: 0.1,
                        color: '#2D6A4F' // sage shadow
                    }
                },
                colors: ['#2D6A4F'], // sage primary color
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.05, // Smooth fade out
                        stops: [0, 95, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { 
                    curve: 'smooth', 
                    width: 3,
                    lineCap: 'round'
                },
                xaxis: {
                    categories: chartData.labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { 
                        style: { colors: '#9ca3af', fontSize: '12px', fontWeight: 600, fontFamily: 'inherit' },
                        offsetY: 5
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "Rp " + (val / 1000000).toFixed(1) + " Jt";
                        },
                        style: { colors: '#9ca3af', fontSize: '11px', fontWeight: 700, fontFamily: 'inherit' },
                        offsetX: -10
                    }
                },
                grid: {
                    borderColor: '#f1f5f9', // subtle slate
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } },
                    xaxis: { lines: { show: false } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit'
                    },
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toLocaleString('id-ID'); // Format as full Rupiah in tooltip
                        }
                    },
                    marker: {
                        show: true,
                    },
                },
                markers: {
                    size: 0,
                    colors: ['#fff'],
                    strokeColors: '#2D6A4F',
                    strokeWidth: 3,
                    hover: {
                        size: 6,
                        sizeOffset: 3
                    }
                }
            };

            const chartArea = document.querySelector("#sppMainChart");
            if (chartArea) {
                const chart = new ApexCharts(chartArea, options);
                chart.render();
            }
        });
    </script>
</x-app-layout>
