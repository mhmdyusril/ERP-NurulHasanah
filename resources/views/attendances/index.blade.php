<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-8 h-8 text-sage"></i>
                    {{ __('Absensi Harian') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Kelola data kehadiran siswa setiap hari secara cepat dan efisien.</p>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 shadow-sm">
            <ul class="list-disc pl-5 font-medium text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter Section (Select Class & Date) -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-8 p-6 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-sage/5 rounded-full blur-2xl -z-10"></div>
        <form action="{{ route('attendances.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-6 relative z-10 w-full" id="filterForm">
            <div class="w-full sm:w-1/3">
                <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Absensi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                    </div>
                    <input type="date" name="date" id="date" value="{{ $date }}" onchange="document.getElementById('filterForm').submit()" class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700">
                </div>
            </div>
            
            <div class="w-full sm:w-1/3">
                <label for="class_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="book-open" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                    </div>
                    <select name="class_id" id="class_id" onchange="document.getElementById('filterForm').submit()" class="block w-full pl-11 pr-10 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700 appearance-none">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $class_id == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="sm:w-1/3 text-right text-sm text-gray-500 font-medium">
                Pilih tanggal dan kelas<br>untuk menampilkan daftar siswa.
            </div>
        </form>
    </div>

    <!-- Attendance Form Area -->
    @if($class_id && $students->count() > 0)
    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="class_id" value="{{ $class_id }}">

        {{-- ── MOBILE CARD LAYOUT ── --}}
        <div class="md:hidden space-y-3">
            @foreach($students as $index => $student)
                @php
                    $att = $student->attendances->first();
                    $status = $att ? $att->status : null;
                    $notes = $att ? $att->notes : '';
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sage/10 to-emerald-900/10 flex items-center justify-center font-bold text-sage flex-shrink-0">
                            {{ substr($student->nama, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900">{{ $student->nama }}</div>
                            <div class="text-xs text-gray-500 font-medium">{{ $student->nis }}</div>
                        </div>
                        @if($att)
                            <div class="text-xs text-gray-400 flex-shrink-0"><i data-lucide="clock" class="w-3 h-3 inline mr-0.5"></i>{{ $att->updated_at->format('H:i') }}</div>
                        @else
                            <span class="text-[10px] text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded-full flex-shrink-0">Belum direkam</span>
                        @endif
                    </div>
                    {{-- Status Radio Buttons (large, finger-friendly) --}}
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        @foreach(['Hadir' => ['bg-emerald-500','border-emerald-500'], 'Sakit' => ['bg-amber-500','border-amber-500'], 'Izin' => ['bg-blue-500','border-blue-500'], 'Alpa' => ['bg-red-500','border-red-500']] as $label => $colors)
                        <label class="cursor-pointer">
                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="{{ $label }}" {{ $status === $label ? 'checked' : '' }} required class="peer sr-only">
                            <div class="text-center py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-{{ Str::before($colors[0],'-5') }} peer-checked:bg-{{ $colors[0] }} peer-checked:text-white text-xs font-bold text-gray-600 transition-all shadow-sm">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    {{-- Keterangan --}}
                    <input type="text" name="attendances[{{ $student->id }}][notes]" value="{{ $notes }}" placeholder="Keterangan tambahan..." class="w-full text-sm border-gray-200 rounded-xl focus:ring-sage focus:border-sage px-3 py-2 bg-gray-50">
                </div>
            @endforeach

            <div class="sticky bottom-20 pt-2">
                <button type="submit" class="w-full py-3.5 bg-sage hover:bg-emerald-800 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sage/20 flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Data Absensi
                </button>
            </div>
        </div>

        {{-- ── DESKTOP TABLE LAYOUT ── --}}
        <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold rounded-tl-xl w-12 text-center">No</th>
                            <th scope="col" class="px-6 py-4 font-bold">Informasi Siswa</th>
                            <th scope="col" class="px-6 py-4 font-bold min-w-[340px]">Status Kehadiran</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Waktu Absen</th>
                            <th scope="col" class="px-6 py-4 font-bold min-w-[200px] rounded-tr-xl">Keterangan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($students as $index => $student)
                            @php
                                $att = $student->attendances->first();
                                $status = $att ? $att->status : null;
                                $notes = $att ? $att->notes : '';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sage/10 to-emerald-900/10 flex items-center justify-center font-bold text-sage">
                                            {{ substr($student->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-base">{{ $student->nama }}</div>
                                            <div class="text-xs text-gray-500 font-medium tracking-wider">{{ $student->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="Hadir" {{ $status === 'Hadir' ? 'checked' : '' }} required class="peer sr-only">
                                            <div class="px-3 py-1.5 rounded-lg border border-gray-200 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 text-xs font-bold text-gray-600 transition-all shadow-sm hover:bg-emerald-50">Hadir</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="Sakit" {{ $status === 'Sakit' ? 'checked' : '' }} required class="peer sr-only">
                                            <div class="px-3 py-1.5 rounded-lg border border-gray-200 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 text-xs font-bold text-gray-600 transition-all shadow-sm hover:bg-amber-50">Sakit</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="Izin" {{ $status === 'Izin' ? 'checked' : '' }} required class="peer sr-only">
                                            <div class="px-3 py-1.5 rounded-lg border border-gray-200 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 text-xs font-bold text-gray-600 transition-all shadow-sm hover:bg-blue-50">Izin</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="Alpa" {{ $status === 'Alpa' ? 'checked' : '' }} required class="peer sr-only">
                                            <div class="px-3 py-1.5 rounded-lg border border-gray-200 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 text-xs font-bold text-gray-600 transition-all shadow-sm hover:bg-red-50">Alpa</div>
                                        </label>
                                    </div>
                                    @if(!$att)
                                        <div class="text-[10px] text-red-500 mt-1.5 font-semibold italic">* Belum direkam</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($att && $att->updated_at)
                                        <div class="inline-flex flex-col items-center justify-center p-2 bg-gray-50 rounded-lg border border-gray-100 shadow-sm text-xs font-semibold text-gray-700">
                                            <i data-lucide="clock" class="w-4 h-4 text-emerald-600 mb-1"></i>
                                            {{ $att->updated_at->format('H:i') }} WIB
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" name="attendances[{{ $student->id }}][notes]" value="{{ $notes }}" placeholder="Keterangan..." class="w-full text-sm border-gray-200 rounded-lg focus:ring-sage focus:border-sage px-3 py-2 bg-gray-50/50">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Footer -->
            <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 flex items-center">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i> Simpan Data Absensi
                </button>
            </div>
        </div>
    </form>
    @elseif($class_id && $students->count() === 0)
        <div class="bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 text-gray-300">
                <i data-lucide="users" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak Ada Siswa</h3>
            <p class="text-gray-500 max-w-sm mx-auto">Kelas yang dipilih belum memiliki murid atau tidak ada data pada kelas ini.</p>
        </div>
    @else
        <div class="bg-white rounded-[2rem] p-12 text-center border border-transparent bg-gradient-to-br from-sage/5 to-transparent">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-sage">
                <i data-lucide="calendar-search" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Pilih Kelas</h3>
            <p class="text-gray-500 max-w-sm mx-auto">Silakan pilih kelas pada filter di atas untuk mulai mengisi input absensi harian.</p>
        </div>
    @endif
</x-app-layout>
