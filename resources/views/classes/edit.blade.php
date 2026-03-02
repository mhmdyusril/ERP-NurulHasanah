<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('classes.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-sage hover:bg-sage/10 rounded-xl transition-colors">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    {{ __('Edit Rombongan Belajar') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Perbarui informasi kelas <strong>{{ $class->nama_kelas }}</strong>.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i data-lucide="edit" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Informasi Kelas</h3>
                        <p class="text-sm text-gray-500">Ubah nama kelas atau ganti penugasan wali kelas di sini.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('classes.update', $class) }}" method="POST" class="p-6 sm:p-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="nama_kelas" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas Singkat</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="book" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $class->nama_kelas) }}" required autofocus
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('nama_kelas') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('nama_kelas') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="wali_kelas_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Wali Kelas (Guru)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user-check" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <select name="wali_kelas_id" id="wali_kelas_id"
                                class="block w-full pl-11 pr-10 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm appearance-none @error('wali_kelas_id') border-red-300 ring-red-100 @enderror">
                                <option value="">-- Kosongkan Jika Belum Ada --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('wali_kelas_id', $class->wali_kelas_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                        @error('wali_kelas_id') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('classes.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 flex items-center">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
