<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-6 h-6 text-emerald-800"></i>
                    {{ __('Presensi Scan QR Code') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pindai kartu identitas santri untuk pencatatan kehadiran harian otomatis.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.cards') }}" class="inline-flex items-center px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 transition shadow-2xs">
                    <i data-lucide="contact" class="w-4 h-4 mr-1.5 text-emerald-800"></i> Cetak Kartu Siswa
                </a>
                <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-900 hover:bg-emerald-800 rounded-lg text-xs font-bold text-white transition shadow-xs">
                    <i data-lucide="list" class="w-4 h-4 mr-1.5"></i> Rekap Manual
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stat Summary Cards Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 mb-6">
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Murid</span>
                <span id="stat-total" class="text-xl font-black text-slate-900 mt-0.5 block">{{ $totalStudents }}</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-emerald-200/80 bg-emerald-50/20 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Hadir</span>
                <span id="stat-hadir" class="text-xl font-black text-emerald-700 mt-0.5 block">{{ $hadir }}</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                <i data-lucide="user-check" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-amber-200/80 bg-amber-50/20 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Sakit</span>
                <span id="stat-sakit" class="text-xl font-black text-amber-700 mt-0.5 block">{{ $sakit }}</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-blue-200/80 bg-blue-50/20 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider block">Izin</span>
                <span id="stat-izin" class="text-xl font-black text-blue-700 mt-0.5 block">{{ $izin }}</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center">
                <i data-lucide="mail" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="col-span-2 sm:col-span-1 bg-white rounded-xl p-4 border border-rose-200/80 bg-rose-50/20 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider block">Belum Hadir</span>
                <span id="stat-belum" class="text-xl font-black text-rose-700 mt-0.5 block">{{ $belumAbsen }}</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Main Scanner & Live Feed Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Camera Viewport & Manual Barcode Input -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Camera Scanner Box -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <h3 class="font-bold text-slate-900 text-sm">Kamera Scanner QR Santri</h3>
                    </div>
                    <button id="btn-toggle-cam" onclick="toggleCamera()" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors flex items-center gap-1.5 border-emerald-800/30 text-emerald-800 bg-emerald-50 hover:bg-emerald-100">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        <span id="btn-cam-text">Nyalakan Kamera</span>
                    </button>
                </div>

                <!-- QR Scanner Render Target (Clean Square 1:1 Viewport) -->
                <div id="qr-reader-container" class="rounded-xl overflow-hidden bg-slate-950 aspect-square relative flex flex-col items-center justify-center text-white border border-slate-800">
                    <div id="qr-reader" class="w-full h-full"></div>
                    
                    <!-- Clean QR Reticle Overlay with 4 Corner Brackets & Laser Scan Line -->
                    <div id="qr-reticle" class="absolute inset-0 pointer-events-none flex items-center justify-center hidden z-20">
                        <div class="relative w-56 h-56 sm:w-64 sm:h-64 flex items-center justify-center">
                            <!-- 4 Corner Brackets (Square QR Frame) -->
                            <div class="absolute top-0 left-0 w-7 h-7 border-t-[3.5px] border-l-[3.5px] border-emerald-400 rounded-tl-md"></div>
                            <div class="absolute top-0 right-0 w-7 h-7 border-t-[3.5px] border-r-[3.5px] border-emerald-400 rounded-tr-md"></div>
                            <div class="absolute bottom-0 left-0 w-7 h-7 border-b-[3.5px] border-l-[3.5px] border-emerald-400 rounded-bl-md"></div>
                            <div class="absolute bottom-0 right-0 w-7 h-7 border-b-[3.5px] border-r-[3.5px] border-emerald-400 rounded-br-md"></div>
                            
                            <!-- Animated Vertical Laser Scanner Line -->
                            <div class="absolute inset-x-2 h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_10px_rgba(52,211,153,0.9)] animate-[scanLaser_2.2s_ease-in-out_infinite]"></div>

                            <span class="absolute -bottom-6 text-[10px] font-bold text-emerald-300 uppercase tracking-widest bg-slate-950/80 px-2 py-0.5 rounded">
                                Posisikan QR Code Santri
                            </span>
                        </div>
                    </div>

                    <div id="qr-placeholder" class="text-center p-6 flex flex-col items-center z-10">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mb-3 text-white border border-white/10">
                            <i data-lucide="qr-code" class="w-7 h-7 text-emerald-400"></i>
                        </div>
                        <h4 class="font-bold text-sm text-slate-200">Kamera Scanner Non-Aktif</h4>
                        <p class="text-xs text-slate-400 max-w-xs mt-1">Arahkan QR code pada kartu santri ke dalam kotak pemindai.</p>
                        <button onclick="toggleCamera()" class="mt-4 px-4 py-2 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition shadow-xs">
                            Aktifkan Kamera Sekarang
                        </button>
                    </div>
                </div>

                <!-- Scanner Instruction Note -->
                <div class="mt-3 flex items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                    <i data-lucide="info" class="w-4 h-4 text-emerald-800 shrink-0"></i>
                    <span>Tahan kartu santri dengan tenang di depan kamera hingga terdengar nada konfirmasi.</span>
                </div>
            </div>

            <!-- Manual Barcode & NIS Input -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center gap-2 mb-1.5">
                    <i data-lucide="keyboard" class="w-4 h-4 text-emerald-800"></i>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Input Kode Santri / Scanner USB</h3>
                </div>
                <p class="text-xs text-slate-500 mb-3 font-medium">Ketik NIS santri atau gunakan hardware scanner barcode/QR USB fisik.</p>

                <form id="manual-scan-form" onsubmit="handleManualSubmit(event)" class="space-y-3">
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="manual-code" placeholder="Contoh NIS: 20260001..." autocomplete="off" autofocus
                                   class="block w-full px-3.5 py-2.5 bg-slate-50 border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 focus:bg-white text-slate-900 font-semibold text-xs sm:text-sm transition-all shadow-2xs">
                        </div>
                        <select id="manual-status" class="w-24 px-2.5 py-2.5 bg-slate-50 border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 font-bold text-xs text-slate-700">
                            <option value="Hadir" selected>Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alpa">Alpa</option>
                        </select>
                        <button type="submit" id="btn-submit-manual" class="px-4 py-2.5 bg-emerald-900 hover:bg-emerald-800 text-white rounded-lg text-xs font-bold transition shadow-xs disabled:opacity-50">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Feedback Banner (Hidden by Default) -->
            <div id="scan-feedback" class="hidden rounded-xl p-4 border transition-all shadow-xs">
                <div class="flex items-start gap-3">
                    <div id="feedback-icon-box" class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                        <i id="feedback-icon" data-lucide="check" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 id="feedback-title" class="font-bold text-xs sm:text-sm"></h4>
                        <div id="feedback-details" class="mt-1.5 text-xs space-y-0.5"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Realtime Attendance Feed -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-xl shadow-xs border border-slate-200/80 overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Aktivitas Presensi Hari Ini</h3>
                        <p class="text-xs text-slate-400">Daftar santri yang baru saja berhasil presensi</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> Live Feed
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Santri</th>
                                <th class="py-3.5 px-4">Kelas</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-log-body" class="divide-y divide-gray-100 text-sm">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3.5 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-sage/10 text-sage font-bold flex items-center justify-center text-xs shrink-0">
                                                {{ substr($log->student->nama ?? 'S', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $log->student->nama ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-400 font-mono">{{ $log->student->nis ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-medium text-gray-600">
                                        {{ $log->student->classroom->nama_kelas ?? 'Belum ada kelas' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($log->status === 'Hadir')
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Hadir</span>
                                        @elseif($log->status === 'Sakit')
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">Sakit</span>
                                        @elseif($log->status === 'Izin')
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">Izin</span>
                                        @else
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-right font-mono text-xs text-gray-500 font-medium">
                                        {{ $log->updated_at->format('H:i:s') }} WIB
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-log-row">
                                    <td colspan="4" class="py-12 text-center text-gray-400 text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Belum ada rekaman presensi santri hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Html5Qrcode Scanner Library via CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let isCameraActive = false;
        let isProcessing = false;

        // Play Beep on Successful Scan (Web Audio API)
        function playBeep(type = 'success') {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                
                if (type === 'success') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime); // High pleasant A5
                    osc.frequency.exponentialRampToValueAtTime(1320, ctx.currentTime + 0.12);
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.16);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                }
            } catch (e) {
                // AudioContext might be restricted by user agent
            }
        }

        // Camera Toggle
        async function toggleCamera() {
            if (isCameraActive) {
                await stopCamera();
            } else {
                await startCamera();
            }
        }

        async function startCamera() {
            const placeholder = document.getElementById('qr-placeholder');
            const reticle = document.getElementById('qr-reticle');
            const btnCamText = document.getElementById('btn-cam-text');

            try {
                // Initialize strictly with QR Code format
                html5QrCode = new Html5Qrcode("qr-reader", {
                    formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
                    verbose: false
                });
                placeholder.classList.add('hidden');
                
                // Pure Square QR Viewfinder
                const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                    const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                    const edgeSize = Math.floor(minEdge * 0.70);
                    return {
                        width: edgeSize,
                        height: edgeSize
                    };
                };

                await html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 15,
                        qrbox: qrboxFunction,
                        aspectRatio: 1.0
                    },
                    (decodedText) => {
                        handleCodeScan(decodedText);
                    },
                    (errorMessage) => {
                        // scanning frame ignore
                    }
                );

                if (reticle) reticle.classList.remove('hidden');
                isCameraActive = true;
                btnCamText.innerText = "Matikan Kamera";
            } catch (err) {
                console.error("Camera start failed:", err);
                placeholder.classList.remove('hidden');
                if (reticle) reticle.classList.add('hidden');
                alert("Tidak dapat mengakses kamera: " + (err.message || err));
                isCameraActive = false;
                btnCamText.innerText = "Nyalakan Kamera";
            }
        }

        async function stopCamera() {
            const reticle = document.getElementById('qr-reticle');
            if (html5QrCode && isCameraActive) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                } catch (e) {
                    console.error("Stop camera error:", e);
                }
            }
            if (reticle) reticle.classList.add('hidden');
            document.getElementById('qr-placeholder').classList.remove('hidden');
            document.getElementById('btn-cam-text').innerText = "Nyalakan Kamera";
            isCameraActive = false;
        }

        // Handle Code Scan (from Camera or Barcode input)
        async function handleCodeScan(code, customStatus = null) {
            if (isProcessing) return;
            isProcessing = true;

            const status = customStatus || document.getElementById('manual-status').value || 'Hadir';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const res = await fetch("{{ route('attendances.scan.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ code, status })
                });

                const data = await res.json();

                if (res.ok && data.status === 'success') {
                    playBeep('success');
                    showFeedback('success', data.message, data.data);
                    updateStats(data.data.stats);
                    prependLog(data.data);
                } else {
                    playBeep('error');
                    showFeedback('error', data.message || 'Kartu santri tidak terdaftar');
                }
            } catch (err) {
                playBeep('error');
                showFeedback('error', 'Gagal memproses presensi: Terjadi gangguan jaringan');
            } finally {
                // Throttle 1.5s before next scan
                setTimeout(() => {
                    isProcessing = false;
                }, 1500);
            }
        }

        // Handle Manual Input Submit
        function handleManualSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('manual-code');
            const code = input.value.trim();
            if (!code) return;

            const status = document.getElementById('manual-status').value;
            handleCodeScan(code, status);
            input.value = '';
            input.focus();
        }

        // Show Feedback Banner
        function showFeedback(type, message, data = null) {
            const banner = document.getElementById('scan-feedback');
            const iconBox = document.getElementById('feedback-icon-box');
            const title = document.getElementById('feedback-title');
            const details = document.getElementById('feedback-details');

            banner.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'bg-rose-50', 'border-rose-200');
            iconBox.classList.remove('bg-emerald-500', 'bg-rose-500');

            if (type === 'success') {
                banner.classList.add('bg-emerald-50', 'border-emerald-200');
                iconBox.classList.add('bg-emerald-500');
                title.className = 'font-bold text-sm text-emerald-900';
                title.innerText = message;

                if (data && data.student) {
                    details.innerHTML = `
                        <div class="text-emerald-800"><span class="font-semibold">Nama:</span> ${data.student.nama}</div>
                        <div class="text-emerald-800"><span class="font-semibold">Kelas:</span> ${data.student.kelas}</div>
                        <div class="text-emerald-800"><span class="font-semibold">Waktu:</span> ${data.time} WIB</div>
                    `;
                }
            } else {
                banner.classList.add('bg-rose-50', 'border-rose-200');
                iconBox.classList.add('bg-rose-500');
                title.className = 'font-bold text-sm text-rose-900';
                title.innerText = message;
                details.innerHTML = `<span class="text-rose-700">Pastikan barcode atau NIS santri telah terdaftar pada modul Kesiswaan.</span>`;
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // Update Stat Counters on Page
        function updateStats(stats) {
            if (!stats) return;
            if (stats.total !== undefined) document.getElementById('stat-total').innerText = stats.total;
            if (stats.hadir !== undefined) document.getElementById('stat-hadir').innerText = stats.hadir;
            if (stats.sakit !== undefined) document.getElementById('stat-sakit').innerText = stats.sakit;
            if (stats.izin !== undefined) document.getElementById('stat-izin').innerText = stats.izin;
            if (stats.belum_absen !== undefined) document.getElementById('stat-belum').innerText = stats.belum_absen;
        }

        // Prepend New Attendance Log to Table
        function prependLog(item) {
            const tbody = document.getElementById('attendance-log-body');
            const noLogRow = document.getElementById('no-log-row');
            if (noLogRow) noLogRow.remove();

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/50 transition-colors animate-fade-in bg-emerald-50/30';

            const statusClass = item.attendance.status === 'Hadir' 
                ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                : (item.attendance.status === 'Sakit' ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-blue-100 text-blue-800 border-blue-200');

            tr.innerHTML = `
                <td class="py-3.5 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-sage/10 text-sage font-bold flex items-center justify-center text-xs shrink-0">
                            ${item.student.nama.substring(0, 1)}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">${item.student.nama}</div>
                            <div class="text-[11px] text-gray-400 font-mono">${item.student.nis}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3.5 px-4 text-xs font-medium text-gray-600">
                    ${item.student.kelas}
                </td>
                <td class="py-3.5 px-4 text-center">
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold border ${statusClass}">${item.attendance.status}</span>
                </td>
                <td class="py-3.5 px-6 text-right font-mono text-xs text-gray-500 font-medium">
                    ${item.time} WIB
                </td>
            `;

            tbody.insertBefore(tr, tbody.firstChild);
        }
    </script>

    <style>
        #qr-reader {
            border: none !important;
            width: 100% !important;
            height: 100% !important;
            background: transparent !important;
        }
        #qr-reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0.75rem !important;
        }
        #qr-shaded-region {
            border-width: 0 !important;
            border: none !important;
            background: rgba(15, 23, 42, 0.35) !important;
        }
        #qr-reader__scan_region {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        @keyframes scanLaser {
            0%, 100% {
                top: 8px;
                opacity: 0.3;
            }
            50% {
                top: calc(100% - 12px);
                opacity: 1;
            }
        }
    </style>
</x-app-layout>
