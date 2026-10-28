<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ERP RA Nurul Hasanah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-emerald-800 selection:text-white">
        
        <!-- Navbar -->
        <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-900 rounded-xl flex items-center justify-center text-white shadow-xs">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-none">RA Nurul Hasanah</span>
                            <span class="text-[10px] font-semibold text-emerald-800 uppercase tracking-widest mt-1">Sistem Informasi Madrasah</span>
                        </div>
                    </div>
                    
                    <div class="hidden md:flex space-x-6 text-xs font-semibold text-slate-600">
                        <a href="#beranda" class="hover:text-emerald-800 transition-colors">Beranda</a>
                        <a href="#tentang" class="hover:text-emerald-800 transition-colors">Tentang Kami</a>
                        <a href="#fitur" class="hover:text-emerald-800 transition-colors">Layanan Sistem</a>
                    </div>

                    <div class="flex items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-emerald-900 hover:bg-emerald-800 transition shadow-xs">
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-emerald-900 hover:bg-emerald-800 transition shadow-xs">
                                    Login Sistem
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="beranda" class="relative pt-28 pb-16 lg:pt-36 lg:pb-24 overflow-hidden border-b border-slate-200/60 bg-gradient-to-b from-white via-slate-50 to-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 border border-emerald-200/70 text-xs font-bold text-emerald-800 mb-6 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    Portal Administrasi & Kesiswaan Terpadu
                </div>
                
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-4xl mx-auto mb-4">
                    Pusat Informasi & Tata Kelola <br class="hidden sm:inline">
                    <span class="text-emerald-900">RA Nurul Hasanah</span>
                </h1>
                
                <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-600 mb-8 leading-relaxed">
                    Sistem terintegrasi untuk pengelolaan data santri, presensi otomatis berbasis scan QR code, administrasi SPP, dan buku tabungan siswa.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-3 max-w-md mx-auto">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg text-xs sm:text-sm font-bold text-white bg-emerald-900 hover:bg-emerald-800 transition shadow-xs">
                        Masuk ke Portal
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-1.5"></i>
                    </a>
                    <a href="#tentang" class="inline-flex items-center justify-center px-6 py-2.5 border border-slate-200 text-xs sm:text-sm font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-2xs">
                        Pelajari Profil Sekolah
                    </a>
                </div>
            </div>
            
            <!-- Dashboard Preview / Carousel -->
            <div class="mt-12 sm:mt-16 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl bg-white p-3 sm:p-4 shadow-sm border border-slate-200/80">
                    <div class="rounded-xl overflow-hidden bg-slate-900 aspect-[16/9] relative flex items-center justify-center">
                        @if($carouselImages && $carouselImages->count() > 0)
                            <div x-data="{ activeSlide: 0, slides: {{ $carouselImages->count() }} }" x-init="setInterval(() => { activeSlide = (activeSlide + 1) % slides }, 5000)" class="w-full h-full relative">
                                @foreach($carouselImages as $index => $image)
                                    <div x-show="activeSlide === {{ $index }}" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-98"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="absolute inset-0">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                        <div class="hidden absolute inset-0 bg-slate-800 flex flex-col items-center justify-center text-slate-400">
                                            <i data-lucide="image-off" class="w-12 h-12"></i>
                                            <p class="text-xs font-bold mt-2">Gambar Gagal Dimuat</p>
                                        </div>
                                        @if($image->title || $image->description)
                                            <div class="absolute bottom-0 inset-x-0 p-4 sm:p-6 bg-gradient-to-t from-black/80 via-black/40 to-transparent text-left text-white">
                                                <h3 class="font-bold text-sm sm:text-base">{{ $image->title }}</h3>
                                                <p class="text-xs opacity-90 mt-0.5">{{ $image->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                
                                <!-- Dots Indicator -->
                                <div class="absolute bottom-3 right-4 flex gap-1.5 z-10">
                                    @foreach($carouselImages as $index => $image)
                                        <button @click="activeSlide = {{ $index }}" :class="activeSlide === {{ $index }} ? 'bg-white w-5' : 'bg-white/50 hover:bg-white'" class="h-1.5 rounded-full transition-all shadow-2xs"></button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400">
                                <i data-lucide="image" class="w-16 h-16 text-slate-300"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang Kami -->
        <section id="tentang" class="py-16 sm:py-24 bg-white border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="text-xs font-bold text-emerald-800 uppercase tracking-widest block mb-2">Profil Kelembagaan</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">Mewujudkan Pendidikan Usia Dini yang Berakhlak & Terkelola Rapi</h2>
                        <p class="text-sm sm:text-base text-slate-600 mb-4 leading-relaxed">
                            Raudhatul Athfal (RA) Nurul Hasanah berkomitmen menghadirkan pembinaan karakter islami dengan dukungan tata kelola administrasi modern, akuntabel, dan transparan.
                        </p>
                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                            Melalui sistem informasi sekolah ini, seluruh riwayat perkembangan santri, presensi harian, tabungan, serta pembayaran SPP dapat dipantau secara akurat dan mudah diakses oleh pihak pengurus.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <div class="p-3.5 rounded-lg border border-slate-200 bg-slate-50">
                                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-800 mb-1.5"></i>
                                <h4 class="font-bold text-xs text-slate-900">Tertib Administrasi</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Arsip data santri & mutasi rapi</p>
                            </div>
                            <div class="p-3.5 rounded-lg border border-slate-200 bg-slate-50">
                                <i data-lucide="wallet" class="w-5 h-5 text-emerald-800 mb-1.5"></i>
                                <h4 class="font-bold text-xs text-slate-900">Transparansi Kas</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Kuitansi & pencatatan digital</p>
                            </div>
                            <div class="p-3.5 rounded-lg border border-slate-200 bg-slate-50">
                                <i data-lucide="qr-code" class="w-5 h-5 text-emerald-800 mb-1.5"></i>
                                <h4 class="font-bold text-xs text-slate-900">Presensi Cepat</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Scan kartu barcode & QR</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100">
                            @php
                                $aboutImage = $carouselImages->first() ? $carouselImages->first()->image_url : 'https://images.unsplash.com/photo-1544605481-229202af5529?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
                            @endphp
                            <img src="{{ $aboutImage }}" alt="Tentang RA Nurul Hasanah" class="w-full h-full object-cover aspect-video sm:aspect-[4/3]">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Sistem -->
        <section id="fitur" class="py-16 sm:py-24 bg-slate-50 border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-widest block mb-1">Modul Utama</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Layanan Terintegrasi Madrasah</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Seluruh kebutuhan pencatatan operasional santri dalam satu platform terpusat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-800 mb-4">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Kesiswaan & Presensi QR</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pencatatan profil santri, generasi kartu identitas resmi dengan QR code unik, dan pencatatan presensi realtime di gerbang sekolah.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-800 mb-4">
                            <i data-lucide="wallet" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Keuangan & Kasir SPP</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Manajemen iuran bulanan SPP, mutasi pelunasan tagihan, penerbitan kuitansi resmi, dan monitoring rekap keuangan secara transparan.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-800 mb-4">
                            <i data-lucide="piggy-bank" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Buku Tabungan Santri</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pencatatan setoran dan penarikan tabungan santri secara akurat dengan mutasi saldo otomatis untuk membiasakan gemar menabung sejak dini.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-7 h-7 bg-emerald-900 rounded-md flex items-center justify-center text-white">
                                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-base text-slate-900 tracking-tight">RA Nurul Hasanah</span>
                        </div>
                        <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                            Raudhatul Athfal Nurul Hasanah — Menyelenggarakan pendidikan anak usia dini berbasis nilai-nilai Islam dengan dukungan tata kelola modern.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-3">Kontak Madrasah</h4>
                        <ul class="space-y-2 text-xs text-slate-500">
                            <li class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i> Jl. Pendidikan No. 123</li>
                            <li class="flex items-center gap-2"><i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i> (021) 123-4567</li>
                            <li class="flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i> info@ranurulhasanah.sch.id</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-3">Tautan Cepat</h4>
                        <ul class="space-y-2 text-xs text-slate-500">
                            <li><a href="#beranda" class="hover:text-emerald-800 transition-colors">Beranda</a></li>
                            <li><a href="#tentang" class="hover:text-emerald-800 transition-colors">Profil Sekolah</a></li>
                            <li><a href="#fitur" class="hover:text-emerald-800 transition-colors">Layanan Sistem</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-emerald-800 transition-colors font-semibold">Login Admin & Guru</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p class="text-slate-400 text-xs">
                        &copy; {{ date('Y') }} RA Nurul Hasanah. All rights reserved.
                    </p>
                    <div class="flex gap-3 text-slate-400">
                        <span class="text-xs text-slate-400">Sistem ERP Madrasah v2.0</span>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
