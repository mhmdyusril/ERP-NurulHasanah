<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel ERP') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://unpkg.com/lucide@latest"></script>
        
        <style>
            .glass-panel {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 25px 50px -12px rgba(45, 106, 79, 0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-float-delayed {
                animation: float 8s ease-in-out infinite;
                animation-delay: 2s;
            }
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-sage selection:text-white bg-cream">
        
        <div class="min-h-screen flex w-full relative overflow-hidden">
            <!-- Left Side / Branding Background (Hidden on Mobile) -->
            <div class="hidden lg:flex w-1/2 relative flex-col justify-center items-center bg-sage overflow-hidden p-12">
                <!-- Decorative pattern background -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs><pattern id="hexagons" width="50" height="43.4" patternUnits="userSpaceOnUse" patternTransform="scale(2)"><path d="M25 43.4L0 21.7L25 0L50 21.7Z" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
                        <rect width="100%" height="100%" fill="url(#hexagons)"/>
                    </svg>
                </div>
                
                <!-- Glowing Orbs -->
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-softTeal rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float-delayed"></div>
                
                <div class="relative z-10 text-center text-white max-w-lg mt-[-10%]">
                    <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-8 border border-white/20 shadow-2xl animate-float">
                        <i data-lucide="graduation-cap" class="w-12 h-12 text-white"></i>
                    </div>
                    <h1 class="text-5xl font-extrabold mb-6 tracking-tight leading-tight">
                        Platform <br>Pendidikan <span class="text-softTeal">Modern</span>
                    </h1>
                    <p class="text-sage-100/80 text-lg font-light leading-relaxed">
                        Sistem manajemen sekolah terpadu yang dirancang khusus untuk mempermudah operasional dan administrasi di lingkungan RA Nurul Hasanah.
                    </p>
                </div>
                
                <div class="absolute bottom-10 left-0 w-full flex justify-center z-10">
                    <div class="flex flex-col items-center">
                        <div class="flex gap-2 mb-3">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            <span class="w-2 h-2 rounded-full bg-white/30"></span>
                            <span class="w-2 h-2 rounded-full bg-white/30"></span>
                        </div>
                        <p class="text-xs text-white/50 font-medium tracking-wider uppercase">ERP RA Nurul Hasanah</p>
                    </div>
                </div>
            </div>
            
            <!-- Right Side / Content Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 relative">
                <!-- Mobile Background Elements -->
                <div class="lg:hidden absolute top-0 left-0 w-full h-[300px] bg-gradient-to-b from-sage/10 to-transparent -z-10"></div>
                <div class="lg:hidden absolute -top-20 -right-20 w-72 h-72 bg-softTeal/20 rounded-full blur-3xl -z-10"></div>
                
                <div class="w-full max-w-md">
                    <!-- Mobile Logo (Hidden on Desktop) -->
                    <div class="lg:hidden text-center mb-8">
                        <a href="/" class="inline-flex justify-center items-center w-16 h-16 bg-sage rounded-2xl shadow-lg shadow-sage/30">
                            <i data-lucide="graduation-cap" class="w-8 h-8 text-white"></i>
                        </a>
                    </div>
                
                    <div class="glass-panel p-8 sm:p-12 rounded-[2.5rem] relative overflow-hidden group">
                        <!-- Top Accent Line -->
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sage via-softTeal to-sage"></div>
                        
                        <div class="mb-10 text-center lg:text-left">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang 👋</h2>
                            <p class="text-gray-500 font-medium">Masuk untuk mengelola sistem pendidikan</p>
                        </div>

                        {{ $slot }}
                        
                    </div>
                    
                    <p class="mt-8 text-center text-sm text-gray-400">
                        &copy; {{ date('Y') }} ERP RA Nurul Hasanah
                    </p>
                </div>
            </div>
        </div>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
