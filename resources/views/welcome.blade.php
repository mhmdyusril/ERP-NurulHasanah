<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ERP RA Nurul Hasanah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-cream selection:bg-sage selection:text-white">
        
        <!-- Navbar -->
        <nav class="fixed w-full z-50 transition-all duration-300 bg-cream/80 backdrop-blur-md border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sage rounded-xl flex items-center justify-center text-white shadow-lg shadow-sage/30">
                            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                        </div>
                        <span class="font-bold text-xl text-sage tracking-tight">RA Nurul Hasanah</span>
                    </div>
                    
                    <div class="hidden md:flex space-x-8">
                        <a href="#beranda" class="text-gray-600 hover:text-sage font-medium transition-colors">Beranda</a>
                        <a href="#tentang" class="text-gray-600 hover:text-sage font-medium transition-colors">Tentang Kami</a>
                        <a href="#keunggulan" class="text-gray-600 hover:text-sage font-medium transition-colors">Keunggulan</a>
                    </div>

                    <div class="flex items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-sage hover:bg-sage/90 shadow-md shadow-sage/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sage">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-sage hover:bg-sage/90 shadow-md shadow-sage/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sage">
                                    Login Sistem
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
            <!-- Background Decorative Elements -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[800px] bg-gradient-to-b from-softTeal/20 to-transparent -z-10"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-sage/10 rounded-full blur-3xl -z-10"></div>
            <div class="absolute top-40 -left-20 w-72 h-72 bg-softTeal/20 rounded-full blur-3xl -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm border border-gray-100 text-sm font-medium text-sage mb-8">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sage opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-sage"></span>
                    </span>
                    Sistem Informasi Terpadu
                </div>
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                    Membangun Generasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sage to-softTeal">Cerdas & Berakhlak</span>
                </h1>
                
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 mb-10">
                    Sistem Perencanaan Sumber Daya Perusahaan (ERP) khusus untuk manajemen Kesiswaan, Keuangan, dan Akademik RA Nurul Hasanah.
                </p>
                
                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-full text-white bg-sage hover:bg-sage/90 shadow-xl shadow-sage/20 transition-all hover:-translate-y-0.5">
                        Mulai Sekarang
                        <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </a>
                    <a href="#tentang" class="inline-flex items-center justify-center px-8 py-3.5 border border-gray-200 text-base font-semibold rounded-full text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            
            <!-- Dashboard Preview / Carousel -->
            <div class="mt-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-[2.5rem] bg-white/50 backdrop-blur-xl p-4 sm:p-6 shadow-2xl border border-white/50 ring-1 ring-black/5">
                    <div class="rounded-[2rem] overflow-hidden bg-gray-50 border border-gray-100 aspect-[16/9] relative flex items-center justify-center">
                        @if($carouselImages && $carouselImages->count() > 0)
                            <div x-data="{ activeSlide: 0, slides: {{ $carouselImages->count() }} }" x-init="setInterval(() => { activeSlide = (activeSlide + 1) % slides }, 5000)" class="w-full h-full relative">
                                @foreach($carouselImages as $index => $image)
                                    <div x-show="activeSlide === {{ $index }}" 
                                         x-transition:enter="transition ease-out duration-500"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-300"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="absolute inset-0">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                        <div class="hidden absolute inset-0 bg-gradient-to-br from-sage/5 to-softTeal/10 flex flex-col items-center justify-center text-sage/30">
                                            <i data-lucide="image-off" class="w-16 h-16"></i>
                                            <p class="text-xs font-bold mt-2">Gambar Gagal Dimuat</p>
                                        </div>
                                        @if($image->title || $image->description)
                                            <div class="absolute bottom-0 inset-x-0 p-6 bg-gradient-to-t from-black/60 to-transparent text-left text-white">
                                                <h3 class="font-bold text-lg">{{ $image->title }}</h3>
                                                <p class="text-xs opacity-90">{{ $image->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                
                                <!-- Dots Indicator -->
                                <div class="absolute bottom-4 right-6 flex gap-2">
                                    @foreach($carouselImages as $index => $image)
                                        <button @click="activeSlide = {{ $index }}" :class="activeSlide === {{ $index }} ? 'bg-sage w-6' : 'bg-white/50 hover:bg-white'" class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-sage/5 to-softTeal/10 flex items-center justify-center">
                                <i data-lucide="layout-dashboard" class="w-24 h-24 text-sage/20"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang Kami -->
        <section id="tentang" class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Tentang ERP RA Nurul Hasanah</h2>
                        <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                            Aplikasi ini dirancang khusus untuk mempermudah tata kelola administrasi di Raudhatul Athfal Nurul Hasanah. Dengan antarmuka yang modern, sistem ini mengintegrasikan berbagai aspek penting sekolah.
                        </p>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Mulai dari manajemen data siswa, pencatatan otomatis SPP, hingga pemantauan perkembangan harian anak dapat dilakukan secara digital dengan mudah dan aman.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-softTeal/20 flex items-center justify-center mr-4 text-sage">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                Efisiensi Administrasi
                            </li>
                            <li class="flex items-center text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-softTeal/20 flex items-center justify-center mr-4 text-sage">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                Transparansi Keuangan
                            </li>
                            <li class="flex items-center text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-softTeal/20 flex items-center justify-center mr-4 text-sage">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                Pemantauan Akademik
                            </li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-sage/20 to-softTeal/20 rounded-[3rem] blur-2xl -z-10"></div>
                        <div class="rounded-[2.5rem] overflow-hidden shadow-2xl">
                            @php
                                $aboutImage = $carouselImages->first() ? $carouselImages->first()->image_url : 'https://images.unsplash.com/photo-1544605481-229202af5529?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
                            @endphp
                            <img src="{{ $aboutImage }}" alt="Tentang RA Nurul Hasanah" class="w-full h-full object-cover aspect-square md:aspect-[4/3] lg:aspect-square">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Keunggulan -->
        <section id="keunggulan" class="py-24 bg-cream relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Fitur Utama Sistem</h2>
                    <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk mengelola sekolah dalam satu platform cerdas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-sage/5 transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-14 h-14 bg-softTeal/20 rounded-2xl flex items-center justify-center text-sage mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kesiswaan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Manajemen data profil siswa, pembagian kelas, riwayat, dan monitoring status aktif siswa secara real-time.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-sage/5 transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-14 h-14 bg-sage/10 rounded-2xl flex items-center justify-center text-sage mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="wallet" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Keuangan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Pencatatan pembayaran SPP, pemantauan status tagihan (lunas/nunggak), dan laporan transaksi keuangan transparan.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-sage/5 transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-14 h-14 bg-softTeal/20 rounded-2xl flex items-center justify-center text-sage mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="book-open" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Akademik</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Jadwal harian kelas, evaluasi, dan input nilai perkembangan anak secara komprehensif oleh para guru.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-sage rounded-lg flex items-center justify-center text-white">
                                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                            </div>
                            <span class="font-bold text-lg text-sage tracking-tight">RA Nurul Hasanah</span>
                        </div>
                        <p class="text-gray-500 max-w-md">
                            Mewujudkan pendidikan anak usia dini yang berkualitas, islami, dan mengikuti perkembangan teknologi modern.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-900 mb-4">Kontak</h4>
                        <ul class="space-y-2 text-gray-500">
                            <li class="flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4"></i> Jl. Pendidikan No. 123</li>
                            <li class="flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i> (021) 123-4567</li>
                            <li class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i> info@ranurulhasanah.sch.id</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-900 mb-4">Tautan</h4>
                        <ul class="space-y-2 text-gray-500">
                            <li><a href="#beranda" class="hover:text-sage transition-colors">Beranda</a></li>
                            <li><a href="#tentang" class="hover:text-sage transition-colors">Tentang Kami</a></li>
                            <li><a href="#keunggulan" class="hover:text-sage transition-colors">Keunggulan</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-sage transition-colors">Login Admin</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} ERP RA Nurul Hasanah. All rights reserved.
                    </p>
                    <div class="flex gap-4 text-gray-400">
                        <a href="#" class="hover:text-sage transition-colors"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-sage transition-colors"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
