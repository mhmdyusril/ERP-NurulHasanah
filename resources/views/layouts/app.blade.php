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
        <meta name="description" content="Sistem Informasi Terpadu RA Nurul Hasanah — Manajemen Siswa, Presensi QR, SPP & Tabungan">

        {{-- ── PWA Manifest & Icons ───────────────────────────────────────── --}}
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512.png">

        {{-- ── Typography: Plus Jakarta Sans ──────────────────────────────── --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            @media (min-width: 768px) {
                .layout-sidebar {
                    transform: translateX(0) !important;
                    position: fixed !important;
                }
                .layout-main-content {
                    margin-left: 260px !important;
                }
            }
        </style>

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

    <body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-emerald-800 selection:text-white">

        {{-- ── Main App Shell ──────────────────────────────────────────────── --}}
        <div x-data="{ sidebarOpen: false, aiOpen: false }" class="min-h-screen bg-slate-50 flex flex-col">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content Wrapper --}}
            <div class="flex flex-col min-h-screen transition-all layout-main-content">
                {{-- Topbar --}}
                @include('layouts.topbar')

                <div class="flex-1 pb-20 md:pb-10">
                    <div class="max-w-7xl mx-auto w-full">
                        {{-- Page Heading --}}
                        @if (isset($header))
                            <div class="px-4 sm:px-6 lg:px-8 pt-6 pb-2">
                                {{ $header }}
                            </div>
                        @endif

                        {{-- Page Content --}}
                        <main class="flex-1 px-4 sm:px-6 lg:px-8 pt-4">
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Mobile Bottom Navigation Bar (visible only on mobile) ──────── --}}
        <nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white/95 backdrop-blur-md border-t border-slate-200"
             style="padding-bottom: env(safe-area-inset-bottom);">
            <div class="flex items-stretch justify-around px-2 h-14">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'text-emerald-800 font-bold' : 'text-slate-500' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Beranda</span>
                </a>

                {{-- Absensi Scan --}}
                <a href="{{ route('attendances.scan') }}"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium transition-colors
                          {{ request()->routeIs('attendances.scan') ? 'text-emerald-800 font-bold' : 'text-slate-500' }}">
                    <i data-lucide="qr-code" class="w-5 h-5"></i>
                    <span>Scan</span>
                </a>

                {{-- Siswa --}}
                <a href="{{ route('students.index') }}"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium transition-colors
                          {{ request()->routeIs('students.*') ? 'text-emerald-800 font-bold' : 'text-slate-500' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Santri</span>
                </a>

                {{-- SPP or Akademik --}}
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('payments.index') }}"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium transition-colors
                          {{ request()->routeIs('payments.*') ? 'text-emerald-800 font-bold' : 'text-slate-500' }}">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    <span>SPP</span>
                </a>
                @else
                <a href="{{ route('academics.index') }}"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium transition-colors
                          {{ request()->routeIs('academics.*') ? 'text-emerald-800 font-bold' : 'text-slate-500' }}">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Nilai</span>
                </a>
                @endif

                {{-- Menu Sidebar Toggle --}}
                <button @click="sidebarOpen = true"
                   class="flex flex-col items-center justify-center gap-0.5 flex-1 px-1 py-1 text-[11px] font-medium text-slate-500">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                    <span>Menu</span>
                </button>

            </div>
        </nav>

        {{-- ── Clean AI Assistant Drawer (Off-Canvas, Non-Intrusive) ───────── --}}
        <div x-show="aiOpen" 
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-hidden bg-slate-900/50 backdrop-blur-xs" 
             style="display: none;">
            
            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10" @click.outside="aiOpen = false">
                <div x-show="aiOpen"
                     x-transition:enter="transform transition ease-out duration-200"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in duration-150"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     x-data="geminiChat()"
                     class="w-screen max-w-md bg-white border-l border-slate-200 shadow-2xl flex flex-col h-full">

                    <!-- Drawer Header -->
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-900 text-white flex items-center justify-center font-bold text-xs">
                                ✨
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 leading-tight">Asisten AI Sekolah</h3>
                                <p class="text-[10px] text-slate-500 font-medium">Bantuan cepat seputar fitur ERP</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="resetChat()" title="Reset Percakapan" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-md transition-colors">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                            <button @click="aiOpen = false" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-md transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50 text-xs">
                        <template x-if="messages.length === 0">
                            <div class="py-8 text-center space-y-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto text-base">
                                    💡
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-xs">Ada yang bisa dibantu, {{ Auth::user()->name }}?</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Tanyakan prosedur penggunaan modul, absensi, atau SPP.</p>
                                </div>
                                <div class="flex flex-col gap-1.5 pt-2 max-w-xs mx-auto">
                                    <template x-for="q in quickQuestions" :key="q">
                                        <button @click="sendQuickQuestion(q)"
                                                class="text-[11px] text-left px-3 py-2 bg-white border border-slate-200 hover:border-emerald-700 hover:bg-emerald-50/30 text-slate-700 rounded-lg transition-colors"
                                                x-text="q"></button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-for="(msg, idx) in messages" :key="idx">
                            <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                                <div :class="msg.role === 'user'
                                        ? 'bg-emerald-900 text-white rounded-xl rounded-tr-xs px-3.5 py-2 max-w-[85%]'
                                        : 'bg-white border border-slate-200 text-slate-800 rounded-xl rounded-tl-xs px-3.5 py-2 max-w-[85%] shadow-2xs'">
                                    <p x-html="formatMessage(msg.text)" class="leading-relaxed break-words"></p>
                                    <span class="block text-[9px] mt-1 text-right" :class="msg.role === 'user' ? 'text-emerald-200' : 'text-slate-400'" x-text="msg.time"></span>
                                </div>
                            </div>
                        </template>

                        <template x-if="loading">
                            <div class="flex justify-start">
                                <div class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-500 text-xs flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-700 rounded-full animate-bounce"></span>
                                    <span class="w-1.5 h-1.5 bg-emerald-700 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                    <span class="w-1.5 h-1.5 bg-emerald-700 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                                    <span class="ml-1 text-[11px]">Memproses jawaban...</span>
                                </div>
                            </div>
                        </template>

                        <template x-if="error">
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 rounded-lg p-2.5 text-[11px]">
                                ⚠️ <span x-text="error"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Drawer Footer Input -->
                    <div class="p-3 border-t border-slate-200 bg-white">
                        <div class="flex gap-2">
                            <textarea x-model="inputText"
                                      @keydown.enter.exact.prevent="sendMessage()"
                                      placeholder="Ketik pertanyaan... (Enter untuk kirim)"
                                      rows="1"
                                      :disabled="loading"
                                      class="flex-1 resize-none bg-slate-50 border border-slate-200 focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 rounded-lg px-3 py-2 text-xs text-slate-800 placeholder-slate-400 outline-none transition-all disabled:opacity-50 max-h-24"></textarea>
                            <button @click="sendMessage()"
                                    :disabled="!inputText.trim() || loading"
                                    class="px-3.5 bg-emerald-900 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold transition-colors disabled:opacity-40 shrink-0 flex items-center justify-center">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── SweetAlert 2 Configuration ──────────────────────────────────── --}}
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
                    confirmButtonColor: '#166534',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-xl shadow-xl border border-slate-200',
                        title: 'font-bold text-slate-900 text-base',
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
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl shadow-xl border border-slate-200',
                            title: 'font-bold text-slate-900 text-base',
                        }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Perhatian',
                        text: '{{ session('error') }}',
                        customClass: {
                            popup: 'rounded-xl shadow-xl border border-slate-200',
                            title: 'font-bold text-slate-900 text-base',
                        }
                    });
                @endif
            });
        </script>
    </body>
</html>
