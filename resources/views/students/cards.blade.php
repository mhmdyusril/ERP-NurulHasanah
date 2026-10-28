<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="contact" class="w-8 h-8 text-sage"></i>
                    {{ __('Cetak Kartu Siswa & QR Presensi') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Cetak kartu identitas resmi dengan kode QR unik untuk absensi santri.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Siswa
                </a>
                <button onclick="window.print()" class="inline-flex items-center justify-center px-5 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Kartu (Print)
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Filter & Search Panel (Hidden during Print) -->
    <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 mb-8 print:hidden">
        <form method="GET" action="{{ route('students.cards') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-5">
                <label for="class_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Filter Kelas</label>
                <div class="relative">
                    <select name="class_id" id="class_id" onchange="this.form.submit()" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage text-sm font-semibold text-gray-700">
                        <option value="">Semua Kelas ({{ $classes->count() }} Kelas)</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $class_id == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="md:col-span-5">
                <label for="search" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cari Nama / NIS</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ketik nama santri atau NIS..."
                           class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage text-sm font-medium text-gray-900">
                </div>
            </div>

            <div class="md:col-span-2 flex items-center justify-between md:justify-end gap-2">
                <button type="submit" class="w-full py-2.5 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl transition shadow-sm">
                    Terapkan
                </button>
            </div>
        </form>

        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan <strong>{{ $students->count() }}</strong> kartu santri aktif</span>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Format cetak terstandarisasi CR80 / Kartu Nama</span>
            </div>
        </div>
    </div>

    <!-- Cards Grid Canvas -->
    <div class="print:m-0 print:p-0">
        @if($students->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 print:grid-cols-2 print:gap-4">
                @foreach($students as $student)
                    <div class="id-card-wrapper bg-white rounded-2xl shadow-sm border-2 border-gray-200 overflow-hidden relative flex flex-col justify-between print:shadow-none print:border-gray-300 print:break-inside-avoid" style="min-height: 220px;">
                        
                        <!-- Card Header (Green Theme with Islamic Motif) -->
                        <div class="bg-gradient-to-r from-sage to-emerald-900 text-white px-4 py-3 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:8px_8px]"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0">
                                        <i data-lucide="graduation-cap" class="w-5 h-5 text-emerald-200"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-xs tracking-wider uppercase leading-tight">RA Nurul Hasanah</h4>
                                        <p class="text-[9px] text-emerald-200 font-medium">KARTU TANDA SANTRI RESMI</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-white/20 border border-white/30 tracking-widest">
                                    PRESENSI
                                </span>
                            </div>
                        </div>

                        <!-- Card Body (Data + QR Code) -->
                        <div class="p-4 flex gap-4 items-center justify-between bg-white relative">
                            <!-- Left: Student Photo Placeholder & Info -->
                            <div class="flex items-center gap-3.5 flex-1 min-w-0">
                                <div class="w-16 h-20 rounded-xl bg-gradient-to-br from-sage/10 to-emerald-800/10 border border-gray-200 flex flex-col items-center justify-center text-sage font-black text-xl shrink-0 shadow-inner overflow-hidden relative">
                                    {{ substr($student->nama, 0, 1) }}
                                    <span class="absolute bottom-1 text-[8px] font-bold text-gray-400 uppercase tracking-tighter">FOTO</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-sm text-gray-900 truncate leading-snug">{{ $student->nama }}</h3>
                                    
                                    <div class="mt-1 space-y-0.5 text-[11px]">
                                        <div class="text-gray-500 flex items-center gap-1.5">
                                            <span class="w-10 font-semibold text-gray-400">NIS:</span>
                                            <span class="font-mono font-bold text-gray-800">{{ $student->nis }}</span>
                                        </div>
                                        <div class="text-gray-500 flex items-center gap-1.5">
                                            <span class="w-10 font-semibold text-gray-400">Kelas:</span>
                                            <span class="font-bold text-sage">{{ $student->classroom->nama_kelas ?? 'Belum ada kelas' }}</span>
                                        </div>
                                        <div class="text-gray-500 flex items-center gap-1.5">
                                            <span class="w-10 font-semibold text-gray-400">Gender:</span>
                                            <span class="font-medium text-gray-700">{{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Rendered QR Code Container -->
                            <div class="flex flex-col items-center justify-center shrink-0 pl-2 border-l border-gray-100">
                                <div class="p-1.5 bg-white border border-gray-200 rounded-xl shadow-xs">
                                    <div id="qrcode-{{ $student->id }}" class="qr-canvas flex items-center justify-center" data-code="{{ $student->nis }}"></div>
                                </div>
                                <span class="text-[8px] font-mono text-gray-400 mt-1 uppercase tracking-tight">Scan Kehadiran</span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex items-center justify-between text-[9px] text-gray-500">
                            <span class="font-medium italic">Tahun Pelajaran 2025/2026</span>
                            <div class="flex items-center gap-1 font-bold text-sage">
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                                <span>Terverifikasi Sekolah</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100">
                <i data-lucide="user-x" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                <h3 class="font-bold text-gray-800 text-lg">Tidak ada data santri ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">Coba sesuaikan filter kelas atau kata kunci pencarian Anda.</p>
            </div>
        @endif
    </div>

    <!-- Printable Styling Rules -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            header, nav, aside, footer, .print\:hidden {
                display: none !important;
            }
            .id-card-wrapper {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin-bottom: 12px !important;
                border: 1px solid #999 !important;
            }
        }
    </style>

    <!-- QRCodeJS Library via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Render QR codes for each student card
            const containers = document.querySelectorAll('.qr-canvas');
            containers.forEach(container => {
                const code = container.getAttribute('data-code');
                if (code) {
                    new QRCode(container, {
                        text: code,
                        width: 68,
                        height: 68,
                        colorDark: "#1B4332",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            });

            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>
