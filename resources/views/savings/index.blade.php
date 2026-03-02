<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="piggy-bank" class="w-8 h-8 text-sage"></i>
                    {{ __('Tabungan Siswa') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Kelola saldo tabungan dan catat transaksi setoran/penarikan siswa.</p>
            </div>
        </div>
    </x-slot>

    <!-- Filter Section -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-8 p-6 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-sage/5 rounded-full blur-2xl -z-10"></div>
        <form action="{{ route('savings.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-6 relative z-10 w-full" id="filterForm">
            <div class="w-full sm:w-1/3">
                <label for="class_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="book-open" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                    </div>
                    <select name="class_id" id="class_id" onchange="document.getElementById('filterForm').submit()" class="block w-full pl-11 pr-10 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700 appearance-none">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $class_id == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="sm:w-2/3 text-right text-sm text-gray-500 font-medium pb-2">
                Menampilkan daftar siswa dan saldo tabungan terkini.
            </div>
        </form>
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($students as $student)
            @php
                $saldo = ($student->total_setoran ?? 0) - ($student->total_tarikan ?? 0);
            @endphp
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative group">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-sage to-emerald-500"></div>
                
                <div class="p-6 border-b border-gray-50 flex-1 flex flex-col justify-center">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-sage/10 to-emerald-900/10 flex items-center justify-center text-xl font-black text-sage shadow-inner border border-white">
                            {{ substr($student->nama, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-sage transition-colors line-clamp-1" title="{{ $student->nama }}">{{ $student->nama }}</h3>
                            <p class="text-sm text-gray-400 font-semibold tracking-wide flex items-center mt-1">
                                <i data-lucide="credit-card" class="w-4 h-4 mr-1.5"></i> {{ $student->nis }}
                            </p>
                            <p class="text-sm text-gray-500 font-medium flex items-center mt-1">
                                <i data-lucide="book-open" class="w-4 h-4 mr-1.5 text-sage"></i> {{ $student->classroom->nama_kelas ?? 'Tanpa Rombel' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <div class="bg-gray-50/80 rounded-xl p-4 flex justify-between items-center border border-gray-100/50">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Saldo</p>
                                <p class="text-xl font-black text-emerald-600 tracking-tight">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <i data-lucide="wallet" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50/50">
                    <a href="{{ route('savings.show', $student->id) }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 hover:border-sage hover:text-sage text-gray-700 rounded-xl text-sm font-bold transition-all shadow-sm group-hover:bg-sage group-hover:text-white group-hover:border-sage group-hover:shadow-sage/20">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Kelola Tabungan
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 text-gray-300">
                    <i data-lucide="users" class="w-10 h-10"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak Ada Data Siswa</h3>
                <p class="text-gray-500 max-w-sm mx-auto">Silakan coba pilih kelas lain atau tambahkan siswa baru.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $students->appends(request()->query())->links() }}
    </div>
</x-app-layout>
