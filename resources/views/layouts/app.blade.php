<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>RA Nurul Hasanah</title>

        {{-- ── PWA Meta Tags ───────────────────────────────────────────────── --}}
        <meta name="theme-color" content="#2D6A4F">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="ERP Nurul Hasanah">
        <meta name="application-name" content="ERP Nurul Hasanah">
        <meta name="msapplication-TileColor" content="#2D6A4F">
        <meta name="description" content="Sistem ERP RA Nurul Hasanah — Manajemen Siswa, Guru, SPP & Akademik">

        {{-- ── PWA Manifest & Icons ───────────────────────────────────────── --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512.png">

        <style>
            /* Fallback layout rules to ensure it works without Tailwind rebuild */
            @media (min-width: 768px) {
                .layout-sidebar {
                    transform: translateX(0) !important;
                    position: fixed !important;
                }
                .layout-main-content {
                    margin-left: 280px !important;
                }
                .layout-gemini-widget {
                    bottom: 1.5rem !important;
                    right: 1.5rem !important;
                }
            }
            @media (max-width: 767px) {
                .layout-gemini-widget {
                    bottom: 5rem !important;
                    right: 1rem !important;
                }
            }
            .layout-gemini-panel {
                width: 24rem !important;
                bottom: 4rem !important;
                right: 0 !important;
            }
            @media (max-width: 640px) {
                .layout-gemini-panel {
                    width: 20rem !important;
                    max-width: calc(100vw - 2rem) !important;
                }
            }
        </style>

        {{-- ── Fonts ──────────────────────────────────────────────────────── --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- ── Vite Assets ────────────────────────────────────────────────── --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- ── Lucide Icons ───────────────────────────────────────────────── --}}
        <script src="https://unpkg.com/lucide@latest"></script>

        {{-- ── Service Worker Registration ─────────────────────────────────── --}}
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => console.log('✅ SW registered:', reg.scope))
                        .catch(err => console.warn('⚠️ SW failed:', err));
                });
            }
        </script>
    </head>

    <body class="font-sans antialiased text-gray-900 selection:bg-sage selection:text-white bg-[#F8F9FA]">

        {{-- ── Main App Shell ──────────────────────────────────────────────── --}}
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-[#F8F9FA] relative">
            {{-- Decorative Background --}}
            <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-softTeal/10 to-transparent rounded-bl-full pointer-events-none -z-10"></div>

            {{-- Sidebar (always fixed, desktop always visible via translate) --}}
            @include('layouts.sidebar')

            {{-- Main Content Wrapper — shifted right on desktop --}}
            <div class="flex flex-col min-h-screen transition-all duration-300 layout-main-content">
                {{-- Topbar --}}
                @include('layouts.topbar')

                <div class="flex-1 overflow-y-auto pb-20 md:pb-0">
                    <div class="max-w-[1600px] mx-auto w-full">
                        {{-- Page Heading --}}
                        @if (isset($header))
                            <div class="px-4 sm:px-6 lg:px-8 pt-8">
                                <header class="bg-white/60 backdrop-blur-xl shadow-sm border border-gray-100/50 rounded-2xl p-6 relative overflow-hidden group">
                                    <div class="absolute inset-y-0 left-0 w-1.5 bg-sage rounded-l-2xl"></div>
                                    {{ $header }}
                                </header>
                            </div>
                        @endif

                        {{-- Page Content --}}
                        <main class="flex-1 p-4 sm:px-6 lg:px-8 pb-12">
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- ── Mobile Bottom Navigation Bar (visible only on mobile) ──────── --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-[0_-4px_24px_rgba(0,0,0,0.06)]"
             style="padding-bottom: env(safe-area-inset-bottom);">
            <div class="flex items-stretch justify-around px-2 h-16">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 rounded-xl transition-all duration-200 group
                          {{ request()->routeIs('dashboard') ? 'text-sage' : 'text-gray-400' }}">
                    <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('dashboard') ? 'scale-110' : '' }} transition-transform">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none">Beranda</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute bottom-1.5 w-1 h-1 bg-sage rounded-full"></span>
                    @endif
                </a>

                {{-- Absensi --}}
                <a href="{{ route('attendances.index') }}"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 rounded-xl transition-all duration-200
                          {{ request()->routeIs('attendances.*') ? 'text-sage' : 'text-gray-400' }}">
                    <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('attendances.*') ? 'scale-110' : '' }} transition-transform">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none">Absensi</span>
                </a>

                {{-- Siswa (center = prominent) --}}
                <a href="{{ route('students.index') }}"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 relative">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sage to-emerald-800 flex items-center justify-center text-white shadow-lg shadow-sage/30 -mt-5 transition-transform active:scale-95">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none {{ request()->routeIs('students.*') ? 'text-sage' : 'text-gray-400' }}">Siswa</span>
                </a>

                {{-- SPP --}}
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('payments.index') }}"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 rounded-xl transition-all duration-200
                          {{ request()->routeIs('payments.*') ? 'text-sage' : 'text-gray-400' }}">
                    <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('payments.*') ? 'scale-110' : '' }} transition-transform">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none">SPP</span>
                </a>
                @else
                <a href="{{ route('academics.index') }}"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 rounded-xl transition-all duration-200
                          {{ request()->routeIs('academics.*') ? 'text-sage' : 'text-gray-400' }}">
                    <div class="w-6 h-6 flex items-center justify-center {{ request()->routeIs('academics.*') ? 'scale-110' : '' }} transition-transform">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none">Akademik</span>
                </a>
                @endif

                {{-- More (buka sidebar) --}}
                <button onclick="document.querySelector('[x-data]').__x.$data.sidebarOpen = true"
                   class="flex flex-col items-center justify-center gap-1 flex-1 px-1 py-2 rounded-xl transition-all duration-200 text-gray-400">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i data-lucide="grid-2x2" class="w-5 h-5"></i>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide leading-none">Menu</span>
                </button>

            </div>
        </nav>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- ── Gemini AI Floating Chat Widget ─────────────────────────────── --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div x-data="geminiChat()" class="fixed z-50 layout-gemini-widget"
             style="margin-bottom: env(safe-area-inset-bottom);">

            {{-- ── Chat Panel ──────────────────────────────────────────────── --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="absolute bottom-16 right-0 bg-white rounded-3xl shadow-2xl shadow-gray-900/15 border border-gray-100 overflow-hidden flex flex-col layout-gemini-panel"
                 style="height: 480px; max-height: calc(100vh - 120px);">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-sage to-emerald-800 p-4 flex items-center gap-3 flex-shrink-0">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" opacity="0.3"/>
                            <path d="M9.5 6.5c0 .83-.67 1.5-1.5 1.5S6.5 7.33 6.5 6.5 7.17 5 8 5s1.5.67 1.5 1.5zM12 1C5.93 1 1 5.93 1 12s4.93 11 11 11 11-4.93 11-11S18.07 1 12 1zm0 2c4.96 0 9 4.04 9 9s-4.04 9-9 9-9-4.04-9-9 4.04-9 9-9z" opacity="0"/>
                            <path d="M21.71 11.29l-9-9a1 1 0 0 0-1.42 0l-9 9a1 1 0 0 0 0 1.42l9 9a1 1 0 0 0 1.42 0l9-9a1 1 0 0 0 0-1.42zM14 14.5V12h-4v3H8v-4c0-.55.45-1 1-1h5V7.5l3.5 3.5-3.5 3.5z" opacity="0"/>
                            <text x="4" y="17" font-size="14" fill="white">✨</text>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white font-bold text-sm leading-tight">Asisten AI Nurul Hasanah</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 bg-green-300 rounded-full animate-pulse"></span>
                            <p class="text-white/80 text-xs font-medium">Llama 3.3 70B · Siap membantu</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="resetChat()" title="Reset chat"
                                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </button>
                        <button @click="open = false"
                                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- Messages Area --}}
                <div x-ref="messagesContainer"
                     class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50">

                    {{-- Welcome message --}}
                    <template x-if="messages.length === 0">
                        <div class="flex flex-col items-center justify-center h-full text-center py-8 gap-4">
                            <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-sage/10 to-softTeal/10 flex items-center justify-center text-3xl">
                                ✨
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Halo, {{ Auth::user()->name }}!</p>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Saya asisten AI untuk ERP ini.<br>Tanyakan apa saja tentang sistem!</p>
                            </div>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <template x-for="q in quickQuestions" :key="q">
                                    <button @click="sendQuickQuestion(q)"
                                            class="text-xs bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full hover:border-sage hover:text-sage transition-all shadow-sm"
                                            x-text="q"></button>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Chat messages --}}
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.role === 'user'
                                    ? 'bg-sage text-white rounded-3xl rounded-br-lg ml-8 px-4 py-2.5 text-sm shadow-sm shadow-sage/20'
                                    : 'bg-white text-gray-800 rounded-3xl rounded-bl-lg mr-8 px-4 py-2.5 text-sm border border-gray-100 shadow-sm'"
                                 class="max-w-full">
                                <p x-html="formatMessage(msg.text)" class="leading-relaxed break-words"></p>
                                <p class="text-[10px] mt-1 font-medium" :class="msg.role === 'user' ? 'text-white/60 text-right' : 'text-gray-400'"
                                   x-text="msg.time"></p>
                            </div>
                        </div>
                    </template>

                    {{-- Typing indicator --}}
                    <template x-if="loading">
                        <div class="flex justify-start">
                            <div class="bg-white border border-gray-100 rounded-3xl rounded-bl-lg px-4 py-3 shadow-sm">
                                <div class="flex gap-1.5 items-center">
                                    <span class="w-2 h-2 bg-sage/60 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                    <span class="w-2 h-2 bg-sage/60 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                    <span class="w-2 h-2 bg-sage/60 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Error state --}}
                    <template x-if="error">
                        <div class="flex justify-start">
                            <div class="bg-red-50 border border-red-100 text-red-600 rounded-3xl rounded-bl-lg mr-8 px-4 py-2.5 text-xs">
                                ⚠️ <span x-text="error"></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Input Area --}}
                <div class="p-3 border-t border-gray-100 bg-white flex-shrink-0">
                    <div class="flex gap-2 items-end">
                        <textarea x-model="inputText"
                                  @keydown.enter.exact.prevent="sendMessage()"
                                  @input="autoResize($el)"
                                  placeholder="Ketik pertanyaan... (Enter untuk kirim)"
                                  rows="1"
                                  :disabled="loading"
                                  class="flex-1 resize-none bg-gray-50 border border-gray-200 focus:border-sage focus:ring-2 focus:ring-sage/20 rounded-2xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all disabled:opacity-60 max-h-28 overflow-y-auto scrollbar-hide"></textarea>
                        <button @click="sendMessage()"
                                :disabled="!inputText.trim() || loading"
                                class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sage to-emerald-800 text-white flex items-center justify-center shadow-md shadow-sage/30 hover:shadow-sage/40 hover:-translate-y-0.5 transition-all disabled:opacity-40 disabled:transform-none flex-shrink-0">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1.5 text-center">Powered by Groq · Llama 3.3 70B · Tekan Enter untuk kirim</p>
                </div>
            </div>

            {{-- ── Toggle Button ────────────────────────────────────────────── --}}
            <button @click="open = !open"
                    style="width: 56px; height: 56px;"
                    class="rounded-2xl bg-gradient-to-br from-sage to-emerald-800 text-white flex items-center justify-center shadow-xl shadow-sage/40 hover:shadow-sage/60 hover:-translate-y-1 active:scale-95 transition-all duration-200 ml-auto relative">
                <span x-show="!open" class="text-2xl leading-none">✨</span>
                <span x-show="open" class="flex items-center justify-center">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </span>
                {{-- Unread dot (only when closed) --}}
                <span x-show="!open && messages.length > 0"
                      class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- ── Scripts ─────────────────────────────────────────────────────── --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <script>
            lucide.createIcons();

            function geminiChat() {
                return {
                    open: false,
                    inputText: '',
                    messages: [],
                    loading: false,
                    error: null,
                    quickQuestions: [
                        'Apa saja fitur ERP ini?',
                        'Cara input absensi siswa?',
                        'Cara catat pembayaran SPP?',
                    ],

                    get history() {
                        return this.messages.map(m => ({ role: m.role === 'assistant' ? 'model' : 'user', text: m.text }));
                    },

                    formatMessage(text) {
                        return text
                            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                            .replace(/\*(.*?)\*/g, '<em>$1</em>')
                            .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-1 rounded text-xs font-mono">$1</code>')
                            .replace(/\n/g, '<br>');
                    },

                    getTime() {
                        return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    },

                    autoResize(el) {
                        el.style.height = 'auto';
                        el.style.height = Math.min(el.scrollHeight, 112) + 'px';
                    },

                    scrollToBottom() {
                        this.$nextTick(() => {
                            const el = this.$refs.messagesContainer;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    },

                    resetChat() {
                        this.messages = [];
                        this.error = null;
                    },

                    sendQuickQuestion(q) {
                        this.inputText = q;
                        this.sendMessage();
                    },

                    async sendMessage() {
                        const text = this.inputText.trim();
                        if (!text || this.loading) return;

                        this.inputText = '';
                        this.error = null;
                        this.messages.push({ role: 'user', text, time: this.getTime() });
                        this.loading = true;
                        this.scrollToBottom();

                        try {
                            const res = await fetch('{{ route("gemini.chat") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    message: text,
                                    history: this.history.slice(-10), // kirim max 10 pesan terakhir
                                }),
                            });

                            const data = await res.json();

                            if (!res.ok || data.error) {
                                this.error = data.error || 'Terjadi kesalahan. Coba lagi.';
                            } else {
                                this.messages.push({ role: 'assistant', text: data.reply, time: this.getTime() });
                                this.error = null;
                                this.$nextTick(() => lucide.createIcons());
                            }
                        } catch (e) {
                            this.error = 'Koneksi gagal. Periksa internet dan coba lagi.';
                        } finally {
                            this.loading = false;
                            this.scrollToBottom();
                        }
                    }
                };
            }
        </script>

        {{-- ── SWEETALERT 2 UNTUK FLASH MESSAGES ── --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Global helper untuk form delete confirmation
            function confirmDelete(event, message) {
                event.preventDefault();
                const form = event.target.closest('form');
                if (!form) return;
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message || 'Apakah Anda yakin ingin melanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#059669', // Emerald-600
                    cancelButtonColor: '#ef4444', // Red-500
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[1.5rem] shadow-xl border border-gray-100',
                        title: 'font-bold text-gray-900',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-[1.5rem] shadow-xl border border-gray-100',
                            title: 'font-bold text-gray-900',
                        }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ session('error') }}',
                        customClass: {
                            popup: 'rounded-[1.5rem] shadow-xl border border-gray-100',
                            title: 'font-bold text-gray-900',
                        }
                    });
                @endif
            });
        </script>
    </body>
</html>
