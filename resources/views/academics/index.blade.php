<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="graduation-cap" class="w-8 h-8 text-sage"></i>
                    {{ __('Akademik & Rapor') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Input nilai perkembangan narasi dan rekap absensi anak didik.</p>
            </div>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-8 p-6 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-sage/5 rounded-full blur-2xl -z-10"></div>
        <form action="{{ route('academics.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-6 relative z-10">
            <div class="w-full sm:w-1/4">
                <label for="academic_year" class="block text-sm font-bold text-gray-700 mb-2">Tahun Ajaran</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                    </div>
                    <select name="academic_year" id="academic_year" class="block w-full pl-11 pr-10 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700 appearance-none">
                        <option value="2025/2026" {{ $year == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                        <option value="2026/2027" {{ $year == '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                    </select>
                </div>
            </div>
            
            <div class="w-full sm:w-1/4">
                <label for="semester" class="block text-sm font-bold text-gray-700 mb-2">Semester</label>
                <select name="semester" id="semester" class="block w-full px-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700 appearance-none">
                    <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <div>
                <button type="submit" class="px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 flex items-center h-[50px]">
                    <i data-lucide="filter" class="w-5 h-5 mr-2"></i> Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Students List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($students as $student)
            @php
                $record = $student->academicRecords->first();
                $isFilled = $record ? true : false;
            @endphp
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative group">
                <!-- Status Banner -->
                @if($isFilled)
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-green-500"></div>
                @else
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-400"></div>
                @endif
                
                <div class="p-6 border-b border-gray-50 flex-1 flex flex-col justify-center">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sage/10 to-emerald-900/10 flex items-center justify-center text-xl font-black text-sage shadow-inner border border-white">
                            {{ substr($student->nama, 0, 1) }}
                        </div>
                        @if($isFilled)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-lg text-xs font-bold shadow-sm">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold shadow-sm">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Menunggu
                            </span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-sage transition-colors line-clamp-1" title="{{ $student->nama }}">{{ $student->nama }}</h3>
                    <p class="text-sm text-gray-400 font-semibold tracking-wide flex items-center mt-1">
                        <i data-lucide="credit-card" class="w-4 h-4 mr-1.5"></i> {{ $student->nis }}
                    </p>
                    <p class="text-sm text-gray-500 font-medium flex items-center mt-1">
                        <i data-lucide="book-open" class="w-4 h-4 mr-1.5 text-sage"></i> {{ $student->classroom->nama_kelas ?? 'Tanpa Rombel' }}
                    </p>
                </div>
                
                <!-- Action / Input Area -->
                <div class="p-4 bg-gray-50/50">
                    <button type="button" onclick="openRaporModal({{ $student->id }}, '{{ addslashes($student->nama) }}', {{ $record ? json_encode($record) : 'null' }})" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 hover:border-sage hover:text-sage text-gray-700 rounded-xl text-sm font-bold transition-all shadow-sm group-hover:bg-sage group-hover:text-white group-hover:border-sage group-hover:shadow-sage/20">
                        <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> {{ $isFilled ? 'Edit Rapor & Absensi' : 'Isi Rapor Sekarang' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 text-gray-300">
                    <i data-lucide="graduation-cap" class="w-10 h-10"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tahun Ajaran Kosong</h3>
                <p class="text-gray-500 max-w-sm mx-auto">Tidak ditemukan siswa pada kriteria filter yang Anda pilih.</p>
            </div>
        @endforelse
    </div>

    <!-- Rapor Modal (Hidden by default) -->
    <div id="raporModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden w-full max-w-3xl max-h-[90vh] flex flex-col m-4 transform scale-95 transition-transform duration-300" id="modalPanel">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-sage/10 rounded-xl flex items-center justify-center text-sage">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">Input Rapor Narasi</h3>
                        <p class="text-xs font-semibold text-sage" id="modal-student-name">Nama Siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeRaporModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <!-- Modal Body (Form) -->
            <form action="{{ route('academics.store') }}" method="POST" class="overflow-y-auto flex-1 p-6">
                @csrf
                <input type="hidden" name="student_id" id="form-student-id">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <input type="hidden" name="academic_year" value="{{ $year }}">
                
                <div class="space-y-6">
                    <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 mb-2">
                        <p class="text-sm font-semibold text-gray-600 mb-1"><i data-lucide="info" class="inline w-4 h-4 mr-1"></i> Keterangan Skala Nilai PAUD:</p>
                        <ul class="text-xs text-gray-500 list-disc pl-5 grid grid-cols-2 gap-x-4">
                            <li><strong>BB:</strong> Belum Berkembang</li>
                            <li><strong>MB:</strong> Mulai Berkembang</li>
                            <li><strong>BSH:</strong> Berkembang Sesuai Harapan</li>
                            <li><strong>BSB:</strong> Berkembang Sangat Baik</li>
                        </ul>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $aspects = [
                                'nilai_agama_moral' => '1. Nilai Agama & Moral',
                                'fisik_motorik' => '2. Fisik Motorik',
                                'kognitif' => '3. Kognitif',
                                'bahasa' => '4. Bahasa',
                                'sosial_emosional' => '5. Sosial Emosional',
                                'seni' => '6. Seni',
                            ];
                            $scales = ['BB', 'MB', 'BSH', 'BSB'];
                        @endphp

                        @foreach($aspects as $key => $label)
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ $label }}</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($scales as $scale)
                                <label class="cursor-pointer">
                                    <input type="radio" name="{{ $key }}" value="{{ $scale }}" id="input-{{ $key }}-{{ $scale }}" class="peer sr-only">
                                    <div class="text-center px-1 py-2 rounded-xl border border-gray-200 peer-checked:bg-sage peer-checked:text-white peer-checked:border-sage transition-all text-sm font-bold shadow-sm hover:border-sage/50">
                                        {{ $scale }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRaporModal()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Rapor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vanilla JS to Handle Modal -->
    <script>
        const modal = document.getElementById('raporModal');
        const modalPanel = document.getElementById('modalPanel');
        
        function openRaporModal(studentId, studentName, recordData) {
            // Set Titles
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('form-student-id').value = studentId;
            
            const aspects = ['nilai_agama_moral', 'fisik_motorik', 'kognitif', 'bahasa', 'sosial_emosional', 'seni'];

            // Populate Fields if edit mode
            if (recordData) {
                aspects.forEach(aspect => {
                    const value = recordData[aspect];
                    if (value) {
                        const radio = document.getElementById(`input-${aspect}-${value}`);
                        if (radio) radio.checked = true;
                    }
                });
            } else {
                // Clear fields for new mode
                aspects.forEach(aspect => {
                    document.querySelectorAll(`input[name="${aspect}"]`).forEach(radio => radio.checked = false);
                });
            }

            // Show Modal with Animation
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modalPanel.classList.remove('scale-95');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeRaporModal() {
            // Hide Modal with Animation
            modal.classList.add('opacity-0');
            modalPanel.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300); // match duration-300
        }
    </script>
</x-app-layout>
