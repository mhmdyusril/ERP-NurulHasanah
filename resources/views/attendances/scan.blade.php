<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-8 h-8 text-sage"></i>
                    {{ __('Presensi Scan QR Code') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Scan kartu QR atau barcode murid untuk pencatatan kehadiran harian otomatis.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('students.cards') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm">
                    <i data-lucide="contact" class="w-4 h-4 mr-2 text-sage"></i> Cetak Kartu Siswa
                </a>
                <a href="{{ route('attendances.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5">
                    <i data-lucide="list" class="w-4 h-4 mr-2"></i> Rekap Absensi Manual
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stat Summary Cards Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Murid</span>
                <span id="stat-total" class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalStudents }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-emerald-100 bg-emerald-50/20 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Hadir Hari Ini</span>
                <span id="stat-hadir" class="text-2xl font-black text-emerald-600 mt-1 block">{{ $hadir }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-100 bg-amber-50/20 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">Sakit</span>
                <span id="stat-sakit" class="text-2xl font-black text-amber-600 mt-1 block">{{ $sakit }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-blue-100 bg-blue-50/20 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block">Izin</span>
                <span id="stat-izin" class="text-2xl font-black text-blue-600 mt-1 block">{{ $izin }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                <i data-lucide="mail" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="col-span-2 sm:col-span-1 bg-white rounded-2xl p-5 shadow-sm border border-rose-100 bg-rose-50/20 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider block">Belum Absen</span>
                <span id="stat-belum" class="text-2xl font-black text-rose-600 mt-1 block">{{ $belumAbsen }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Main Scanner & Live Feed Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Camera Viewport & Manual Barcode Input -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Camera Scanner Box -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <h3 class="font-bold text-gray-900 text-base">Kamera Scanner QR</h3>
                    </div>
                    <button id="btn-toggle-cam" onclick="toggleCamera()" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors flex items-center gap-1.5 border-sage/20 text-sage bg-sage/5 hover:bg-sage/10">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        <span id="btn-cam-text">Nyalakan Kamera</span>
                    </button>
                </div>

                <!-- QR Scanner Render Target -->
                <div id="qr-reader-container" class="rounded-2xl overflow-hidden bg-gray-950 aspect-[4/3] relative flex flex-col items-center justify-center text-white border border-gray-800">
                    <div id="qr-reader" class="w-full h-full"></div>
                    <div id="qr-placeholder" class="text-center p-6 flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-3 text-sage">
                            <i data-lucide="scan" class="w-8 h-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-sm text-gray-200">Kamera Siap Diaktifkan</h4>
                        <p class="text-xs text-gray-400 max-w-xs mt-1">Klik tombol di atas untuk mengaktifkan pemindaian otomatis kartu santri.</p>
                        <button onclick="toggleCamera()" class="mt-4 px-4 py-2 bg-sage hover:bg-emerald-800 text-white rounded-xl text-xs font-bold transition shadow-md">
                            Aktifkan Sekarang
                        </button>
                    </div>
                </div>

                <!-- Scanner Instruction Note -->
                <div class="mt-4 flex items-center gap-2 text-xs text-gray-500 bg-gray-50 p-3 rounded-xl">
                    <i data-lucide="info" class="w-4 h-4 text-gray-400 shrink-0"></i>
                    <span>Arahkan kode QR kartu santri ke dalam kotak viewfinder. Audio konfirmasi akan berbunyi saat terbaca.</span>
                </div>
            </div>

            <!-- Manual Barcode & NIS Input -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="barcode" class="w-5 h-5 text-sage"></i>
                    <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Input Barcode / NIS Manual</h3>
                </div>
                <p class="text-xs text-gray-500 mb-4 font-medium">Gunakan barcode scanner USB fisik atau ketik NIS murid secara manual.</p>

                <form id="manual-scan-form" onsubmit="handleManualSubmit(event)" class="space-y-3">
                    <div class="flex gap-2">
                        <div class="relative flex-1 group">
                            <input type="text" id="manual-code" placeholder="Contoh: 20260001..." autocomplete="off" autofocus
                                   class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white text-gray-900 font-semibold text-sm transition-all shadow-sm">
                        </div>
                        <select id="manual-status" class="w-28 px-3 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage font-bold text-xs text-gray-700">
                            <option value="Hadir" selected>Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alpa">Alpa</option>
                        </select>
                        <button type="submit" id="btn-submit-manual" class="px-5 py-3 bg-sage hover:bg-emerald-800 text-white rounded-xl text-sm font-bold transition shadow-md shadow-sage/20 disabled:opacity-50">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Feedback Banner (Hidden by Default) -->
            <div id="scan-feedback" class="hidden rounded-2xl p-5 border transition-all animate-fade-in shadow-sm">
                <div class="flex items-start gap-4">
                    <div id="feedback-icon-box" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                        <i id="feedback-icon" data-lucide="check" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 id="feedback-title" class="font-bold text-sm"></h4>
                        <div id="feedback-details" class="mt-2 text-xs space-y-1"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Realtime Attendance Feed -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Aktivitas Presensi Hari Ini</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar kehadiran yang baru saja dicatat secara otomatis</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Update
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
            const btnCamText = document.getElementById('btn-cam-text');

            try {
                html5QrCode = new Html5Qrcode("qr-reader");
                placeholder.classList.add('hidden');
                
                await html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.333334
                    },
                    (decodedText) => {
                        handleCodeScan(decodedText);
                    },
                    (errorMessage) => {
                        // scanning frame ignore
                    }
                );

                isCameraActive = true;
                btnCamText.innerText = "Matikan Kamera";
            } catch (err) {
                console.error("Camera start failed:", err);
                placeholder.classList.remove('hidden');
                alert("Tidak dapat mengakses kamera: " + (err.message || err));
                isCameraActive = false;
                btnCamText.innerText = "Nyalakan Kamera";
            }
        }

        async function stopCamera() {
            if (html5QrCode && isCameraActive) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                } catch (e) {
                    console.error("Stop camera error:", e);
                }
            }
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
</x-app-layout>
