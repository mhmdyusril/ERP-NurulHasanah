<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-8 h-8 text-sage"></i>
                    {{ __('Pusat Rekapitulasi') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Laporan terpadu absensi, pembayaran SPP, dan transaksi tabungan.</p>
            </div>
            
            <a href="{{ route('reports.export', ['start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate), 'class_id' => request('class_id', $class_id)]) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Cetak Laporan
            </a>
        </div>
    </x-slot>

    <!-- Filter Form (Hidden in print) -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 mb-8 print:hidden">
        <form action="{{ route('reports.finance') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Filter Tanggal Mulai -->
            <div class="flex-1 w-full relative">
                <label class="block text-sm font-bold text-gray-700 mb-2">Periode Mulai</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-sage focus:border-sage sm:text-sm font-semibold text-gray-700">
                </div>
            </div>
            <!-- Filter Tanggal Selesai -->
            <div class="flex-1 w-full relative">
                <label class="block text-sm font-bold text-gray-700 mb-2">Periode Selesai</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}"
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-sage focus:border-sage sm:text-sm font-semibold text-gray-700">
                </div>
            </div>
            <!-- Filter Kelas -->
            <div class="flex-1 w-full relative">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="building" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <select name="class_id" class="block w-full pl-11 pr-8 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-sage focus:border-sage sm:text-sm font-semibold text-gray-700 appearance-none">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id', $class_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="w-full md:w-auto mt-4 md:mt-0">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-bold rounded-xl transition-all shadow-md flex justify-center items-center h-[46px]">
                    <i data-lucide="filter" class="w-5 h-5 sm:mr-2"></i>
                    <span class="inline">Terapkan Rentang</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Print Header (Visible only in print) -->
    <div class="hidden print:block text-center mb-8 border-b-2 border-gray-800 pb-4">
        <h1 class="text-2xl font-black uppercase tracking-widest text-gray-900">ERP RA Nurul Hasanah</h1>
        <h2 class="text-lg font-bold text-gray-700 mt-1">Laporan Rekapitulasi Terpadu</h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('DD MMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('DD MMM Y') }}</p>
    </div>

    <!-- TAB SYSTEM ALPINE JS -->
    <div x-data="{ activeTab: 'absensi' }">
        <!-- Tabs Navigation -->
        <div class="flex space-x-2 border-b border-gray-200 mb-8 print:hidden overflow-x-auto print:border-none print:mb-2">
            <button @click="activeTab = 'absensi'" :class="{'bg-sage text-white shadow-md': activeTab === 'absensi', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'absensi'}" class="px-5 py-3 rounded-t-xl font-bold flex items-center gap-2 transition-colors whitespace-nowrap outline-none">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                Rekap Absensi
            </button>
            <button @click="activeTab = 'spp'" :class="{'bg-sage text-white shadow-md': activeTab === 'spp', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'spp'}" class="px-5 py-3 rounded-t-xl font-bold flex items-center gap-2 transition-colors whitespace-nowrap outline-none">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                Setoran SPP
            </button>
            <button @click="activeTab = 'tabungan'" :class="{'bg-sage text-white shadow-md': activeTab === 'tabungan', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'tabungan'}" class="px-5 py-3 rounded-t-xl font-bold flex items-center gap-2 transition-colors whitespace-nowrap outline-none">
                <i data-lucide="piggy-bank" class="w-5 h-5"></i>
                Transaksi Tabungan
            </button>
        </div>

        <!-- TAB CONTENT: ABSENSI -->
        <div x-show="activeTab === 'absensi'" class="print:block">
            <h3 class="font-bold text-xl text-sage mb-4 hidden print:block border-b pb-2">Rekapitulasi Kehadiran Siswa</h3>
            
            {{-- ── MOBILE CARD LAYOUT ── --}}
            <div class="md:hidden space-y-3 mb-8">
                @forelse($students as $index => $stu)
                    @php
                        $hadir = $stu->attendances->where('status', 'Hadir')->count();
                        $sakit = $stu->attendances->where('status', 'Sakit')->count();
                        $izin = $stu->attendances->where('status', 'Izin')->count();
                        $alpa = $stu->attendances->where('status', 'Alpa')->count();
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm">
                                {{ substr($stu->nama, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-900 truncate">{{ $stu->nama }}</div>
                                <div class="text-xs text-gray-400 font-semibold">{{ $stu->nis }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div class="text-center p-2 rounded-xl {{ $hadir > 0 ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }}">
                                <div class="text-xs font-bold text-gray-500 mb-0.5">Hadir</div>
                                <div class="text-lg font-black {{ $hadir > 0 ? 'text-emerald-600' : 'text-gray-300' }}">{{ $hadir }}</div>
                            </div>
                            <div class="text-center p-2 rounded-xl {{ $sakit > 0 ? 'bg-amber-50 border border-amber-100' : 'bg-gray-50 border border-gray-100' }}">
                                <div class="text-xs font-bold text-gray-500 mb-0.5">Sakit</div>
                                <div class="text-lg font-black {{ $sakit > 0 ? 'text-amber-600' : 'text-gray-300' }}">{{ $sakit }}</div>
                            </div>
                            <div class="text-center p-2 rounded-xl {{ $izin > 0 ? 'bg-blue-50 border border-blue-100' : 'bg-gray-50 border border-gray-100' }}">
                                <div class="text-xs font-bold text-gray-500 mb-0.5">Izin</div>
                                <div class="text-lg font-black {{ $izin > 0 ? 'text-blue-600' : 'text-gray-300' }}">{{ $izin }}</div>
                            </div>
                            <div class="text-center p-2 rounded-xl {{ $alpa > 0 ? 'bg-red-50 border border-red-100' : 'bg-gray-50 border border-gray-100' }}">
                                <div class="text-xs font-bold text-gray-500 mb-0.5">Alpa</div>
                                <div class="text-lg font-black {{ $alpa > 0 ? 'text-red-600' : 'text-gray-300' }}">{{ $alpa }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm text-gray-500">
                        Tidak ada data absen pada periode ini.
                    </div>
                @endforelse
            </div>

            {{-- ── DESKTOP TABLE LAYOUT ── --}}
            <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8 print:shadow-none print:border-none print:rounded-none">
                <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100 sticky top-0">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold rounded-tl-xl w-12 text-center">No</th>
                                <th scope="col" class="px-6 py-4 font-bold">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 text-center font-bold text-emerald-600">Hadir</th>
                                <th scope="col" class="px-6 py-4 text-center font-bold text-amber-600">Sakit</th>
                                <th scope="col" class="px-6 py-4 text-center font-bold text-blue-600">Izin</th>
                                <th scope="col" class="px-6 py-4 text-center font-bold text-red-600">Alpa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($students as $index => $stu)
                                @php
                                    $hadir = $stu->attendances->where('status', 'Hadir')->count();
                                    $sakit = $stu->attendances->where('status', 'Sakit')->count();
                                    $izin = $stu->attendances->where('status', 'Izin')->count();
                                    $alpa = $stu->attendances->where('status', 'Alpa')->count();
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 text-base">{{ $stu->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $stu->nis }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $hadir > 0 ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-gray-100 text-gray-400 font-medium' }}">{{ $hadir }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $sakit > 0 ? 'bg-amber-100 text-amber-700 font-bold' : 'bg-gray-100 text-gray-400 font-medium' }}">{{ $sakit }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $izin > 0 ? 'bg-blue-100 text-blue-700 font-bold' : 'bg-gray-100 text-gray-400 font-medium' }}">{{ $izin }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $alpa > 0 ? 'bg-red-100 text-red-700 font-bold' : 'bg-gray-100 text-gray-400 font-medium' }}">{{ $alpa }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Pilih kelas/data siswa tidak ditemukan pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: SPP -->
        <div x-show="activeTab === 'spp'" style="display: none;" class="print:block print:mt-12">
            <h3 class="font-bold text-xl text-sage mb-4 hidden print:block border-b pb-2">Laporan Pembayaran SPP & Tagihan</h3>
            <!-- Summary Cards SPP -->
            <div class="mb-4">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl shadow-lg p-6 text-white w-full md:w-1/3 relative overflow-hidden print:border print:border-green-200 print:text-green-900 print:bg-none print:shadow-none">
                    <div class="absolute -right-4 -bottom-4 opacity-10 print:hidden">
                        <i data-lucide="banknote" class="w-32 h-32"></i>
                    </div>
                    <p class="text-emerald-100 font-bold uppercase tracking-wider text-xs mb-1 print:text-green-600">Total Tagihan (SPP dll)</p>
                    <h3 class="text-3xl font-black">Rp {{ number_format($totalSPP, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- ── MOBILE CARD LAYOUT (SPP) ── --}}
            <div class="md:hidden space-y-3 mt-4">
                @forelse($payments as $p)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 text-gray-600 rounded-full text-[10px] font-bold border border-gray-200">
                                <i data-lucide="calendar" class="w-3 h-3"></i> {{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-bold border border-green-100">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Lunas
                            </span>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-0.5">{{ $p->student->nama }}</h4>
                        <p class="text-xs text-gray-500 font-medium mb-3">{{ $p->billingCategory->name ?? 'SPP Bulanan' }}</p>
                        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nominal Disetor</span>
                            <span class="font-black text-emerald-600 text-lg">Rp {{ number_format($p->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm text-gray-500">
                        Tidak ada transaksi pelunasan di periode ini.
                    </div>
                @endforelse
            </div>

            <!-- Tabel Tagihan (Payments) -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100 sticky top-0">
                            <tr>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500">Tanggal Lunas</th>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500">Nama Siswa</th>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500">Kategori / Deskripsi</th>
                                <th scope="col" class="px-5 py-4 font-bold text-right text-emerald-600">Nominal Disetor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($payments as $p)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-3 whitespace-nowrap text-gray-600 font-bold">{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}</td>
                                <td class="px-5 py-3 font-bold text-gray-900">{{ $p->student->nama }}</td>
                                <td class="px-5 py-3 text-gray-600 font-medium">{{ $p->billingCategory->name ?? 'SPP Bulanan' }}</td>
                                <td class="px-5 py-3 text-right font-black text-emerald-600 bg-emerald-50/30">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-500 font-medium">Tidak ada transaksi pelunasan di periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: TABUNGAN -->
        <div x-show="activeTab === 'tabungan'" style="display: none;" class="print:block print:mt-12">
            <h3 class="font-bold text-xl text-sage mb-4 hidden print:block border-b pb-2">Laporan Transaksi Tabungan</h3>
            <!-- Summary Cards Savings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 w-full">
                <!-- Wajib -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden print:border print:border-purple-200 print:text-purple-900 print:bg-none print:shadow-none">
                    <div class="absolute -right-4 -bottom-4 opacity-10 print:hidden">
                        <i data-lucide="check-circle" class="w-32 h-32"></i>
                    </div>
                    <p class="text-purple-100 font-bold uppercase tracking-wider text-xs mb-1 print:text-purple-600">Total Setoran Wajib</p>
                    <h3 class="text-3xl font-black shadow-purple-900/20 text-shadow-sm">Rp {{ number_format($totalSetoranWajib, 0, ',', '.') }}</h3>
                </div>

                <!-- Bebas -->
                <div class="bg-gradient-to-br from-indigo-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden print:border print:border-blue-200 print:text-blue-900 print:bg-none print:shadow-none">
                    <div class="absolute -right-4 -bottom-4 opacity-10 print:hidden">
                        <i data-lucide="plus-circle" class="w-32 h-32"></i>
                    </div>
                    <p class="text-indigo-100 font-bold uppercase tracking-wider text-xs mb-1 print:text-blue-600">Total Setoran Bebas</p>
                    <h3 class="text-3xl font-black shadow-indigo-900/20 text-shadow-sm">Rp {{ number_format($totalSetoranBebas, 0, ',', '.') }}</h3>
                </div>

                <!-- Penarikan -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden print:border print:border-orange-200 print:text-orange-900 print:bg-none print:shadow-none">
                    <div class="absolute -right-4 -bottom-4 opacity-10 print:hidden">
                        <i data-lucide="arrow-up-circle" class="w-32 h-32"></i>
                    </div>
                    <p class="text-amber-100 font-bold uppercase tracking-wider text-xs mb-1 print:text-orange-600">Total Penarikan (Keluar)</p>
                    <h3 class="text-3xl font-black shadow-orange-900/20 text-shadow-sm">Rp {{ number_format($totalTarikan, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- ── MOBILE CARD LAYOUT (TABUNGAN) ── --}}
            <div class="md:hidden space-y-3 mt-4 mb-4">
                @forelse($savings as $s)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 text-gray-600 rounded-full text-[10px] font-bold border border-gray-200">
                                <i data-lucide="calendar" class="w-3 h-3"></i> {{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}
                            </span>
                            @if($s->type === 'Setor')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100">
                                    <i data-lucide="arrow-down-right" class="w-3.5 h-3.5"></i> Setor
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-100">
                                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i> Tarik
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900">{{ $s->student->nama }}</h4>
                            @if($s->type === 'Setor')
                                @if($s->kategori === 'Wajib')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-bold uppercase border border-purple-200">Wajib</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase border border-blue-200">Bebas</span>
                                @endif
                            @endif
                        </div>
                        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nominal</span>
                            <span class="font-black text-lg {{ $s->type === 'Setor' ? 'text-indigo-600' : 'text-amber-600' }}">
                                {{ $s->type === 'Setor' ? '+' : '-' }} Rp {{ number_format($s->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm text-gray-500">
                        Tidak ada transaksi tabungan di periode ini.
                    </div>
                @endforelse
            </div>

            <!-- Tabel Tabungan (Savings) -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:rounded-none xl:w-2/3">
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100 sticky top-0">
                            <tr>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500">Tanggal</th>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500">Nama Siswa</th>
                                <th scope="col" class="px-5 py-4 font-bold text-gray-500 text-center">Jenis Transaksi</th>
                                <th scope="col" class="px-5 py-4 font-bold text-right text-gray-700">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($savings as $s)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-3 whitespace-nowrap text-gray-600 font-bold">{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
                                <td class="px-5 py-3 font-bold text-gray-900">{{ $s->student->nama }}</td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($s->type === 'Setor')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold shadow-sm">
                                                <i data-lucide="arrow-down-right" class="w-3 h-3"></i> Setor
                                            </span>
                                            @if($s->kategori === 'Wajib')
                                                <span class="inline-flex items-center px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-bold uppercase tracking-wider border border-purple-200">Wajib</span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase tracking-wider border border-blue-200">Bebas</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold shadow-sm">
                                                <i data-lucide="arrow-up-right" class="w-3 h-3"></i> Tarik
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right font-black {{ $s->type === 'Setor' ? 'text-indigo-600 bg-indigo-50/30' : 'text-amber-600 bg-amber-50/30' }}">
                                    {{ $s->type === 'Setor' ? '+' : '-' }} Rp {{ number_format($s->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-500 font-medium">Tidak ada transaksi tabungan di periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
