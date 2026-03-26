<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="building" class="w-8 h-8 text-sage"></i>
                    {{ __('Data Kelas') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Manajemen data rombongan belajar (rombel) dan wali kelas.</p>
            </div>
            
            <a href="{{ route('classes.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kelas Baru
            </a>
        </div>
    </x-slot>

    {{-- ── MOBILE CARD LAYOUT ── --}}
    <div class="md:hidden space-y-3 mt-4">
        @forelse ($classes as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage shadow-sm border border-white">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-900">{{ $item->nama_kelas }}</div>
                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        {{ $item->waliKelas->name ?? 'Belum Ditentukan' }}
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-bold mt-1">{{ $item->students_count }} Siswa</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('classes.edit', $item) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                    </a>
                    <form action="{{ route('classes.destroy', $item) }}" method="POST" class="inline" onsubmit="confirmDelete(event, 'Hapus kelas ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
                <i data-lucide="building" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="font-bold text-gray-900 mb-1">Data Kelas Kosong</p>
                <a href="{{ route('classes.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sage text-white text-sm font-semibold rounded-xl mt-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kelas Pertama
                </a>
            </div>
        @endforelse
    </div>

    {{-- ── DESKTOP TABLE LAYOUT ── --}}
    <div class="hidden md:block bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Nama Kelas</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Wali Kelas</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Jumlah Siswa</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($classes as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm border border-white">
                                        <i data-lucide="book-open" class="w-5 h-5"></i>
                                    </div>
                                    <div class="font-bold text-gray-900 text-base group-hover:text-sage transition-colors">{{ $item->nama_kelas }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ $item->waliKelas ? substr($item->waliKelas->name, 0, 1) : '?' }}
                                    </div>
                                    <span class="text-gray-900 font-medium">{{ $item->waliKelas->name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full font-bold">{{ $item->students_count }} Siswa</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('classes.edit', $item) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Data">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('classes.destroy', $item) }}" method="POST" class="inline" onsubmit="confirmDelete(event, 'Hapus kelas ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Kelas">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                    <i data-lucide="building" class="w-10 h-10 text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Data Kelas Kosong</h3>
                                <p class="text-gray-500 max-w-sm mx-auto mb-6">Belum ada data rombongan belajar yang diinputkan ke sistem.</p>
                                <a href="{{ route('classes.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kelas Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
