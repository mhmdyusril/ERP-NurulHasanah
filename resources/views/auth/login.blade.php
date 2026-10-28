<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" 
          x-data="{ submitting: false }" 
          @submit="submitting = true; if (typeof startPageProgress === 'function') startPageProgress();"
          class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border-slate-200 text-slate-900 rounded-lg text-xs sm:text-sm focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 focus:bg-white transition-all shadow-2xs font-medium"
                    placeholder="nama@ranurulhasanah.sch.id"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-700 transition-colors">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border-slate-200 text-slate-900 rounded-lg text-xs sm:text-sm focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 focus:bg-white transition-all shadow-2xs font-medium"
                    placeholder="••••••••"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center pt-1">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-800 focus:ring-emerald-700 transition-colors cursor-pointer bg-slate-50">
            <label for="remember_me" class="ml-2 text-xs font-medium text-slate-600 cursor-pointer select-none">
                Ingat saya di perangkat ini
            </label>
        </div>

        <div class="pt-3">
            <button type="submit" 
                    :disabled="submitting"
                    class="w-full flex justify-center items-center px-4 py-2.5 bg-emerald-900 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm rounded-lg transition-all shadow-xs disabled:opacity-60 disabled:cursor-wait">
                <template x-if="submitting">
                    <div class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memverifikasi Akun...</span>
                    </div>
                </template>
                <template x-if="!submitting">
                    <div class="flex items-center">
                        <span>Masuk ke Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-1.5"></i>
                    </div>
                </template>
            </button>
        </div>
    </form>
</x-guest-layout>
