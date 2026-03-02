<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('students.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-sage hover:bg-sage/10 rounded-xl transition-colors">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    {{ __('Edit Data Siswa') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Perbarui informasi identitas profil siswa <strong>{{ $student->nama }}</strong>.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <!-- Form Header & Progress -->
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i data-lucide="edit" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Informasi Pribadi Siswa</h3>
                        <p class="text-sm text-gray-500">Perhatikan NIS agar tidak terjadi duplikasi pendaftaran.</p>
                    </div>
                </div>
            </div>

            <!-- The Form -->
            <form action="{{ route('students.update', $student) }}" method="POST" class="p-6 sm:p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Siswa</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $student->nama) }}" required autofocus
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('nama') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('nama') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor Induk Siswa (NIS) -->
                    <div>
                        <label for="nis" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Siswa (NIS)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="hash" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="text" name="nis" id="nis" value="{{ old('nis', $student->nis) }}" required
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('nis') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('nis') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                        <div class="flex items-center gap-6 mt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'L' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Laki-Laki (L)</span>
                            </label>
                            
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'P' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Perempuan (P)</span>
                            </label>
                        </div>
                        @error('jenis_kelamin') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kelas Penempatan -->
                    <div>
                        <label for="class_id" class="block text-sm font-semibold text-gray-700 mb-2">Penempatan Kelas</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="book-open" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <select name="class_id" id="class_id"
                                class="block w-full pl-11 pr-10 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm appearance-none @error('class_id') border-red-300 ring-red-100 @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                        @error('class_id') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $student->tempat_lahir) }}"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('tempat_lahir') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('tempat_lahir') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tgl_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $student->tgl_lahir) }}"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('tgl_lahir') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('tgl_lahir') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Orang Tua / Wali -->
                    <div>
                        <label for="nama_wali" class="block text-sm font-semibold text-gray-700 mb-2">Nama Orang Tua / Wali</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="users" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <input type="text" name="nama_wali" id="nama_wali" value="{{ old('nama_wali', $student->nama_wali) }}"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm @error('nama_wali') border-red-300 ring-red-100 @enderror">
                        </div>
                        @error('nama_wali') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Domisili)</label>
                        <div class="relative group">
                            <div class="absolute top-3 left-4 pointer-events-none">
                                <i data-lucide="home" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                            </div>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm resize-none @error('alamat') border-red-300 ring-red-100 @enderror">{{ old('alamat', $student->alamat) }}</textarea>
                        </div>
                        @error('alamat') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('students.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2 flex items-center">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
