<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-8 h-8 text-sage"></i>
                    {{ __('Manajemen Data Guru') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Kelola akun dan akses guru secara menyeluruh.</p>
            </div>
            
            <a href="{{ route('teachers.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Guru
            </a>
        </div>
    </x-slot>

    {{-- ── MOBILE CARD LAYOUT ── --}}
    <div class="md:hidden space-y-3">
        @forelse ($teachers as $teacher)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold text-base shadow-sm border border-white">
                    {{ substr($teacher->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-900 truncate">{{ $teacher->name }}</div>
                    <div class="text-xs text-gray-500 truncate mt-0.5">{{ $teacher->email }}</div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-[10px] font-bold mt-1">
                        <i data-lucide="shield-check" class="w-3 h-3"></i> GURU
                    </span>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </a>
                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="inline" onsubmit="confirmDelete(event, 'Hapus akun guru ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
                <i data-lucide="users" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="font-medium text-gray-500">Belum ada data guru.</p>
            </div>
        @endforelse
        @if ($teachers->hasPages())
            <div class="py-4">{{ $teachers->links() }}</div>
        @endif
    </div>

    {{-- ── DESKTOP TABLE LAYOUT ── --}}
    <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Nama Guru</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Akses</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($teachers as $teacher)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm border border-white">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                    <div class="font-extrabold text-gray-900 text-sm group-hover:text-sage transition-colors">{{ $teacher->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $teacher->email }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-bold shadow-sm">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> GURU
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors" title="Edit Data">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="inline" onsubmit="confirmDelete(event, 'Apakah Anda yakin ingin menghapus akun guru ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Hapus Data">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="users" class="w-12 h-12 text-gray-300 mb-3"></i>
                                    <p class="font-medium">Belum ada data guru ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($teachers->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">{{ $teachers->links() }}</div>
        @endif
    </div>
</x-app-layout>
