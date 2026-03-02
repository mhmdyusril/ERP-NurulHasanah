<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('students.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-sage hover:bg-sage/10 rounded-xl transition-colors">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                        {{ __('Detail Data Siswa') }}
                    </h2>
                    <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Informasi lengkap profil dan riwayat akademik <strong>{{ $student->nama }}</strong>.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-sm font-semibold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Edit Data
                </a>
                <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-sage hover:bg-emerald-800 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Profil
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto flex flex-col md:flex-row gap-6">
        
        <!-- Left Column (Profile Summary) -->
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden sticky top-32">
                <div class="relative h-32 bg-gradient-to-br from-sage to-emerald-800">
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs><pattern id="circles" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="white"/></pattern></defs>
                            <rect width="100%" height="100%" fill="url(#circles)"/>
                        </svg>
                    </div>
                </div>
                
                <div class="px-6 pb-6 text-center relative">
                    <!-- Avatar -->
                    <div class="w-24 h-24 bg-white rounded-[1.5rem] border-4 border-white shadow-xl flex items-center justify-center mx-auto -mt-12 relative z-10 text-4xl font-black text-sage bg-gradient-to-br from-gray-50 to-gray-100">
                        {{ substr($student->nama, 0, 1) }}
                        <div class="absolute bottom-[-4px] right-[-4px] w-6 h-6 bg-green-500 rounded-full border-4 border-white"></div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-4 mb-1">{{ $student->nama }}</h3>
                    <p class="text-sm font-medium text-sage mb-4">NIS: {{ $student->nis }}</p>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Kelas Aktif</p>
                            <p class="font-bold text-gray-900 flex items-center justify-center gap-1.5">
                                <i data-lucide="book-open" class="w-4 h-4 text-sage"></i>
                                {{ $student->classroom->nama_kelas ?? 'Belum Ada' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Status</p>
                            <p class="font-bold text-green-600 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Aktif
                            </p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 text-left">
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Tempat Lahir</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->tempat_lahir ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar" class="w-5 h-5 text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Tanggal Lahir</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->tgl_lahir ? \Carbon\Carbon::parse($student->tgl_lahir)->format('d F Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Details Tab) -->
        <div class="w-full md:w-2/3 space-y-6">
            
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <i data-lucide="user" class="w-5 h-5 text-sage"></i>
                    <h3 class="text-lg font-bold text-gray-900">Alamat & Kontak</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4 border border-gray-100">
                        {{ $student->alamat ?: 'Alamat belum diisi.' }}
                    </p>
                </div>
            </div>
            
            <!-- Keuangan Overview Placeholder -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                    <i data-lucide="wallet" class="w-5 h-5 text-sage"></i>
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Pembayaran Terakhir</h3>
                </div>
                <div class="p-6">
                    <div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                            <i data-lucide="info" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-orange-800">Modul Belum Terhubung</h4>
                            <p class="text-xs text-orange-600 mt-1">Data riwayat pembayaran SPP siswa ini akan muncul setelah Modul Keuangan selesai dikembangkan.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
