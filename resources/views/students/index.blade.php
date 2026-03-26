<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="users" class="w-8 h-8 text-sage"></i>
                    {{ __('Data Kesiswaan') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Manajemen data siswa, kelas, dan pendaftaran baru RA Nurul Hasanah.</p>
            </div>
            
            <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Siswa Baru
            </a>
        </div>
    </x-slot>

    <!-- Filters & Search Bar -->
    <div class="bg-white rounded-[1.5rem] p-4 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
            <a href="{{ route('students.index') }}" class="px-4 py-2 {{ !request('status') || request('status') === 'Aktif' ? 'bg-sage text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }} rounded-lg text-sm font-semibold whitespace-nowrap transition-all">Siswa Aktif</a>
            <a href="{{ route('students.index', ['status' => 'Lulus']) }}" class="px-4 py-2 {{ request('status') === 'Lulus' ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }} rounded-lg text-sm font-semibold whitespace-nowrap transition-all">Alumni (Lulus)</a>
            <a href="{{ route('students.index', ['status' => 'Keluar']) }}" class="px-4 py-2 {{ request('status') === 'Keluar' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }} rounded-lg text-sm font-semibold whitespace-nowrap transition-all">Keluar</a>
        </div>
        
        <form method="GET" action="{{ route('students.index') }}" class="w-full md:w-80 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-sage transition-colors"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama Siswa..." 
                class="block w-full pl-10 pr-3 py-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-colors sm:text-sm">
        </form>
    </div>

    {{-- ── MOBILE CARD LAYOUT (hidden on md+) ── --}}
    <div class="md:hidden space-y-3">
        @forelse ($students as $student)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col gap-3">
                {{-- Top row: avatar + name + status --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-sage/20 to-softTeal/20 flex flex-shrink-0 items-center justify-center text-sage font-bold text-base shadow-sm border border-white">
                        {{ substr($student->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-gray-900 flex items-center gap-1.5 flex-wrap">
                            <span class="truncate">{{ $student->nama }}</span>
                            @if($student->jenis_kelamin === 'L')
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">L</span>
                            @elseif($student->jenis_kelamin === 'P')
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-pink-600 border border-pink-100">P</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tgl_lahir)->format('d M Y') }}</div>
                    </div>
                    @if($student->status === 'Aktif')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                        </span>
                    @elseif($student->status === 'Lulus')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 flex-shrink-0">
                            <i data-lucide="graduation-cap" class="w-3 h-3"></i> Lulus
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100 flex-shrink-0">
                            <i data-lucide="log-out" class="w-3 h-3"></i> Keluar
                        </span>
                    @endif
                </div>

                {{-- Info row: NIS + Kelas --}}
                <div class="flex gap-3 text-xs">
                    <div class="flex items-center gap-1.5 bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100">
                        <i data-lucide="hash" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="font-semibold text-gray-700">{{ $student->nis }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100">
                        <i data-lucide="book-open" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="font-semibold text-gray-700">{{ $student->classroom->nama_kelas ?? 'Belum Ada Kelas' }}</span>
                    </div>
                </div>

                {{-- Action row --}}
                <div class="flex gap-2 pt-1 border-t border-gray-50">
                    <a href="{{ route('students.show', $student) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-sage/10 text-sage text-xs font-semibold hover:bg-sage hover:text-white transition-all">
                        <i data-lucide="eye" class="w-4 h-4"></i> Detail
                    </a>
                    <a href="{{ route('students.edit', $student) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold hover:bg-blue-600 hover:text-white transition-all">
                        <i data-lucide="edit" class="w-4 h-4"></i> Edit
                    </a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data siswa ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-600 hover:text-white transition-all">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="users-rounded" class="w-8 h-8 text-gray-300"></i>
                </div>
                <p class="font-bold text-gray-900 mb-1">Data Siswa Kosong</p>
                <p class="text-sm text-gray-500 mb-4">Belum ada data siswa yang diinputkan.</p>
                <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sage text-white text-sm font-semibold rounded-xl">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Siswa Pertama
                </a>
            </div>
        @endforelse

        @if($students->hasPages())
            <div class="py-4">{{ $students->links() }}</div>
        @endif
    </div>

    {{-- ── DESKTOP TABLE LAYOUT (hidden on mobile) ── --}}
    <div class="hidden md:block bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Siswa</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">NIS</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kelas</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sage/20 to-softTeal/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm border border-white">
                                        {{ substr($student->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 group-hover:text-sage transition-colors flex items-center gap-2">
                                            {{ $student->nama }}
                                            @if($student->jenis_kelamin === 'L')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">L</span>
                                            @elseif($student->jenis_kelamin === 'P')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-pink-600 border border-pink-100">P</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tgl_lahir)->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">{{ $student->nis }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="book-open" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-900 font-medium">{{ $student->classroom->nama_kelas ?? 'Belum Ada Kelas' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($student->status === 'Aktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>
                                @elseif($student->status === 'Lulus')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100"><i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Lulus</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100"><i data-lucide="log-out" class="w-3.5 h-3.5"></i> Keluar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('students.show', $student) }}" class="p-1.5 text-gray-400 hover:text-sage hover:bg-sage/10 rounded-lg transition-colors" title="Detail Siswa">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Data">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    @if($student->status === 'Aktif')
                                    <form action="{{ route('students.updateStatus', $student) }}" method="POST" class="inline-block">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Lulus">
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Luluskan Siswa" onclick="return confirm('Luluskan {{ $student->nama }}?')">
                                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('students.updateStatus', $student) }}" method="POST" class="inline-block">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Aktif">
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Aktifkan Kembali" onclick="return confirm('Aktifkan kembali {{ $student->nama }}?')">
                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data siswa ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Siswa">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                    <i data-lucide="users-rounded" class="w-10 h-10 text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Data Siswa Kosong</h3>
                                <p class="text-gray-500 max-w-sm mx-auto mb-6">Belum ada data siswa yang diinputkan ke dalam sistem.</p>
                                <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Siswa Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
