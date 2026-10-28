<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- PWA --}}
        <meta name="theme-color" content="#2D6A4F">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="ERP Nurul Hasanah">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icons/icon-192.png">

        <title>{{ config('app.name', 'RA Nurul Hasanah') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans text-slate-800 antialiased selection:bg-emerald-800 selection:text-white bg-slate-50">
        
        <!-- Top Loading Progress Bar -->
        <div id="page-progress-bar" class="fixed top-0 left-0 right-0 h-[2.5px] z-[9999] pointer-events-none opacity-0 transition-opacity duration-200">
            <div id="page-progress-bar-inner" class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)] transition-all duration-300 ease-out" style="width: 0%;"></div>
        </div>

        <div class="min-h-screen flex w-full relative">
            <!-- Left Side / Branding Background (Hidden on Mobile) -->
            <div class="hidden lg:flex w-1/2 relative flex-col justify-between bg-emerald-950 p-12 text-white border-r border-emerald-900/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-emerald-800 rounded-lg flex items-center justify-center text-white shadow-xs">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-sm tracking-tight block leading-tight">RA Nurul Hasanah</span>
                        <span class="text-[10px] text-emerald-300 font-semibold uppercase tracking-wider">Portal ERP Madrasah</span>
                    </div>
                </div>
                
                <div class="max-w-md my-auto">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-2">Sistem Informasi Terpadu</span>
                    <h1 class="text-3xl font-extrabold mb-4 tracking-tight leading-tight">
                        Tata Kelola Administrasi & Kesiswaan Berstandar Modern
                    </h1>
                    <p class="text-emerald-200/80 text-xs sm:text-sm leading-relaxed">
                        Mendukung kelancaran presensi santri via scan kartu QR, monitoring pembayaran SPP, dan buku tabungan digital secara aman dan transparan.
                    </p>
                </div>
                
                <div class="text-xs text-emerald-400/60 font-medium">
                    &copy; {{ date('Y') }} RA Nurul Hasanah. All rights reserved.
                </div>
            </div>
            
            <!-- Right Side / Content Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo (Hidden on Desktop) -->
                    <div class="lg:hidden text-center mb-6">
                        <a href="/" class="inline-flex justify-center items-center w-12 h-12 bg-emerald-900 text-white rounded-xl shadow-xs">
                            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                        </a>
                    </div>
                
                    <div class="bg-white p-6 sm:p-8 rounded-xl border border-slate-200/80 shadow-xs relative">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-slate-900 mb-1">Masuk ke Sistem</h2>
                            <p class="text-xs text-slate-500 font-medium">Gunakan akun admin atau guru terdaftar</p>
                        </div>

                        {{ $slot }}
                    </div>
                    
                    <p class="mt-6 text-center text-xs text-slate-400">
                        Kembali ke <a href="/" class="text-emerald-800 font-bold hover:underline">Halaman Utama</a>
                    </p>
                </div>
            </div>
        </div>

        <script>
            lucide.createIcons();

            // Page & Form Progress Bar Controller
            const progressBar = document.getElementById('page-progress-bar');
            const progressBarInner = document.getElementById('page-progress-bar-inner');
            let progressInterval = null;

            function startPageProgress() {
                if (!progressBar || !progressBarInner) return;
                clearInterval(progressInterval);
                progressBar.style.opacity = '1';
                progressBarInner.style.transition = 'width 300ms ease-out';
                progressBarInner.style.width = '25%';

                let current = 25;
                progressInterval = setInterval(() => {
                    if (current < 85) {
                        current += Math.random() * 12;
                        progressBarInner.style.width = current + '%';
                    }
                }, 200);
            }

            function finishPageProgress() {
                if (!progressBar || !progressBarInner) return;
                clearInterval(progressInterval);
                progressBarInner.style.transition = 'width 150ms ease-out';
                progressBarInner.style.width = '100%';
                setTimeout(() => {
                    progressBar.style.opacity = '0';
                    setTimeout(() => {
                        progressBarInner.style.width = '0%';
                    }, 200);
                }, 150);
            }

            window.addEventListener('pageshow', function() {
                finishPageProgress();
            });
        </script>
    </body>
</html>
