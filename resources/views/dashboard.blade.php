<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-slate-900 tracking-tight">
                    {{ __('Dashboard Utama') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ringkasan operasional santri, absensi, dan keuangan RA Nurul Hasanah</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('attendances.scan') }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-900 hover:bg-emerald-800 text-white rounded-lg text-xs font-bold transition shadow-xs">
                    <i data-lucide="qr-code" class="w-4 h-4 mr-1.5"></i>
                    Scan Presensi
                </a>
                <a href="{{ route('students.cards') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-xs font-semibold transition shadow-xs">
                    <i data-lucide="contact" class="w-4 h-4 mr-1.5 text-emerald-800"></i>
                    Cetak Kartu
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('reports.finance') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-xs font-semibold transition shadow-xs">
                    <i data-lucide="printer" class="w-4 h-4 mr-1.5 text-slate-400"></i>
                    Laporan
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Executive Greeting Banner -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-6 mb-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200/60 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                Tahun Ajaran 2026/2027 · Semester Ganjil
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-slate-900">Assalamu'alaikum, {{ Auth::user()->name }}</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Sistem informasi manajemen madrasah siap digunakan untuk kegiatan operasional hari ini.</p>
        </div>
        <div class="flex items-center gap-2 self-stretch sm:self-auto justify-end">
            <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 rounded-lg text-xs font-bold transition">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1.5"></i> Tambah Santri
            </a>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Santri Aktif</span>
                <div class="w-9 h-9 bg-emerald-50 text-emerald-800 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h4 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($totalSiswa ?? 0, 0, ',', '.') }}</h4>
                <span class="text-xs text-slate-400 font-medium">Santri terdaftar</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">SPP Masuk (Bulan Ini)</span>
                <div class="w-9 h-9 bg-emerald-50 text-emerald-800 rounded-lg flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h4 class="text-2xl font-black text-slate-900 tracking-tight">Rp {{ number_format($totalDanaMasuk ?? 0, 0, ',', '.') }}</h4>
                <span class="text-xs text-emerald-700 font-semibold">Terkumpul</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Guru & Staf Pengajar</span>
                <div class="w-9 h-9 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center">
                    <i data-lucide="contact" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h4 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($guruAktif ?? 0, 0, ',', '.') }}</h4>
                <span class="text-xs text-slate-400 font-medium">Pegawai aktif</span>
            </div>
        </div>
    </div>
    
    <!-- Main Dashboard Interactive Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bar Chart Widget -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 lg:col-span-2 flex flex-col shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Grafik Arus Kas SPP</h3>
                    <p class="text-xs text-slate-400 font-medium">Statistik penerimaan 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md">Semester Berjalan</span>
            </div>
            
            <!-- Chart Container -->
            <div class="flex-1 w-full min-h-[300px]" id="sppMainChart"></div>
        </div>
        
        <!-- Interactive Timeline Widget -->
        <div class="bg-white rounded-xl border border-slate-200/80 flex flex-col shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900">Aktivitas Sistem</h3>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Terbaru</span>
            </div>
            
            <div class="p-4 flex-1">
                <div class="space-y-4">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start gap-3 text-xs">
                            <div class="w-7 h-7 rounded-lg {{ $activity['bg'] }} flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="{{ $activity['icon'] }}" class="w-3.5 h-3.5 {{ $activity['color'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <h4 class="font-bold text-slate-800 truncate">{{ $activity['title'] }}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono shrink-0">{{ $activity['time'] }}</span>
                                </div>
                                <p class="text-slate-500 leading-relaxed">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-10">
                            <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                            <p class="text-xs text-slate-400">Belum ada aktivitas baru.</p>
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
