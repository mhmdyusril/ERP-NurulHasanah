<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="image" class="w-8 h-8 text-sage"></i>
                    {{ __('Manajemen Carousel Landing Page') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Kelola gambar dan konten promosi pada halaman depan.</p>
            </div>
            <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2.5 bg-sage hover:bg-emerald-800 rounded-xl text-sm font-semibold text-white transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Gambar
            </button>
        </div>
    </x-slot>

    {{-- ── MOBILE CARD LAYOUT ── --}}
    <div class="md:hidden space-y-4">
        @forelse ($images as $image)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-40 w-full bg-gray-100 relative group-hover:border-sage/30">
                    <img src="{{ $image->image_url }}" alt="Preview" class="w-full h-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                    <div class="hidden flex flex-col items-center justify-center text-red-400 absolute inset-0 bg-red-50">
                        <i data-lucide="image-off" class="w-8 h-8 mb-2"></i>
                        <span class="text-xs font-bold text-red-500">Link Gambar Salah</span>
                    </div>
                    {{-- Status Badge (Absolute) --}}
                    <div class="absolute top-3 right-3">
                        @if($image->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-500/90 backdrop-blur-sm text-white rounded-full text-[10px] font-bold shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i> AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-800/80 backdrop-blur-sm text-white rounded-full text-[10px] font-bold shadow-sm">
                                <i data-lucide="x" class="w-3 h-3"></i> NON-AKTIF
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-gray-900 text-sm truncate">{{ $image->title ?: 'Tanpa Judul' }}</h3>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $image->image_url }}</p>
                        </div>
                        <div class="shrink-0 text-center">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Urutan</span>
                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-800 font-black rounded">{{ $image->order }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-3 border-t border-gray-50">
                        <button onclick='openEditModal(@json($image))' class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-xl transition-colors text-xs">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                        </button>
                        <form action="{{ route('carousel.destroy', $image->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus gambar ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition-colors text-xs">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
                <i data-lucide="image" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="font-medium text-gray-500">Belum ada gambar carousel.</p>
            </div>
        @endforelse
    </div>

    {{-- ── DESKTOP TABLE LAYOUT ── --}}
    <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Preview</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Judul & Keterangan</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Urutan</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Status</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($images as $image)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="w-24 h-16 rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 flex items-center justify-center relative group-hover:border-sage/30 transition-colors">
                                    <img src="{{ $image->image_url }}" alt="Preview" class="w-full h-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden flex flex-col items-center justify-center text-red-400">
                                        <i data-lucide="image-off" class="w-6 h-6"></i>
                                        <span class="text-[8px] mt-1 font-bold">Link Salah</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-gray-900 text-sm group-hover:text-sage transition-colors">{{ $image->title ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400 truncate max-w-xs font-medium">{{ $image->image_url }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $image->order }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($image->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-bold shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> AKTIF
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 text-gray-700 border border-gray-200 rounded-full text-xs font-bold shadow-sm">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i> NON-AKTIF
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openEditModal(@json($image))' class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </button>
                                    <form action="{{ route('carousel.destroy', $image->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus gambar ini dari carousel?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="image" class="w-12 h-12 text-gray-300 mb-3"></i>
                                    <p class="font-medium">Belum ada gambar carousel ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add -->
    <div id="modal-add" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full p-8 pt-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-6 h-6 text-sage"></i>
                        Tambah Gambar Carousel
                    </h3>
                    <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form action="{{ route('carousel.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Tips Link Gambar
                            </h4>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                Link harus berupa <b>Direct Link</b> (langsung ke file gambar). Google Photos/Drive tidak disarankan karena linknya diproteksi.
                                Gunakan <b>Postimages.org</b> atau <b>ImgBB.com</b>, lalu pilih opsi <b>"Direct Link"</b> (link yang berakhiran .jpg atau .png).
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">URL Direct Gambar <span class="text-red-500">*</span></label>
                            <input type="url" name="image_url" required placeholder="https://i.postimg.cc/image.jpg" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul (Opsional)</label>
                            <input type="text" name="title" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="description" rows="2" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Urutan</label>
                            <input type="number" name="order" value="0" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-sage hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-sage/20 transition-all">Simpan Gambar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modal-edit" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8 pt-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="edit" class="w-6 h-6 text-sage"></i>
                        Edit Gambar Carousel
                    </h3>
                    <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">URL Gambar <span class="text-red-500">*</span></label>
                            <input type="url" name="image_url" id="edit-image_url" required class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul (Opsional)</label>
                            <input type="text" name="title" id="edit-title" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="description" id="edit-description" rows="2" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Urutan</label>
                                <input type="number" name="order" id="edit-order" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                <select name="is_active" id="edit-is_active" class="w-full rounded-xl border-gray-200 focus:border-sage focus:ring-sage shadow-sm text-sm p-3">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-sage hover:bg-emerald-800 text-white font-bold rounded-xl shadow-lg shadow-sage/20 transition-all">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(image) {
            const form = document.getElementById('form-edit');
            form.action = `/carousel/${image.id}`;
            document.getElementById('edit-image_url').value = image.image_url;
            document.getElementById('edit-title').value = image.title || '';
            document.getElementById('edit-description').value = image.description || '';
            document.getElementById('edit-order').value = image.order;
            document.getElementById('edit-is_active').value = image.is_active;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
</x-app-layout>
