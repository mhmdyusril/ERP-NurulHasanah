<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 selection:bg-sage selection:text-white bg-[#F8F9FA]">
        <div class="min-h-screen flex w-full relative overflow-hidden bg-[#F8F9FA]">
            <!-- Decorative Backgrounds -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-softTeal/10 to-transparent rounded-bl-full pointer-events-none -z-10"></div>
            
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 w-full overflow-y-auto">
                <!-- Topbar -->
                @include('layouts.topbar')

                <div class="max-w-[1600px] mx-auto w-full">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <div class="px-4 sm:px-6 lg:px-8 pt-8">
                            <header class="bg-white/60 backdrop-blur-xl shadow-sm border border-gray-100/50 rounded-2xl p-6 relative overflow-hidden group">
                                <div class="absolute inset-y-0 left-0 w-1.5 bg-sage rounded-l-2xl"></div>
                                {{ $header }}
                            </header>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <main class="flex-1 p-4 sm:px-6 lg:px-8 pb-12">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
