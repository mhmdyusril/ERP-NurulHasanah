<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-sage transition-colors mb-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Data Guru
                </a>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="edit" class="w-8 h-8 text-sage"></i>
                    {{ __('Edit Data Guru') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Perbarui informasi profil dan kata sandi akun guru.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-8 p-6 md:p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-amber-100 to-transparent rounded-full blur-3xl -z-10 -translate-x-1/2 -translate-y-1/2"></div>
            
            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="name" value="Nama Lengkap Guru" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $teacher->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Alamat Email (Digunakan untuk Login)" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $teacher->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 font-medium mb-4"><i data-lucide="info" class="w-3 h-3 inline mr-1"></i>Biarkan kosong jika tidak ingin mengubah kata sandi.</p>
                    </div>

                    <div>
                        <x-input-label for="password" value="Kata Sandi Baru" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-bold rounded-xl transition-all shadow-lg hover:-translate-y-0.5 flex items-center">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i> Perbarui Data Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
