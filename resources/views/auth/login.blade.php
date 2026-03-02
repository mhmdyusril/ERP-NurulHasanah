<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-5 w-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm"
                    placeholder="nama@ranurulhasanah.sch.id"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-sage hover:text-sage/80 transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="lock" class="h-5 w-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm"
                    placeholder="••••••••"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-4">
            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-sage focus:ring-sage/50 transition-colors cursor-pointer bg-gray-50">
            <label for="remember_me" class="ml-3 text-sm font-medium text-gray-600 cursor-pointer select-none">
                Biarkan saya tetap masuk
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full relative overflow-hidden group flex justify-center items-center px-6 py-4 bg-sage text-white font-bold text-base rounded-xl transition-all shadow-lg shadow-sage/30 hover:shadow-xl hover:shadow-sage/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-sage/30">
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[100%] group-hover:animate-[shimmer_1.5s_infinite]"></span>
                <span>Masuk Sekarang</span>
                <i data-lucide="arrow-right" class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1"></i>
            </button>
            <style>
                @keyframes shimmer {
                    100% { transform: translateX(100%); }
                }
            </style>
        </div>
    </form>
</x-guest-layout>
