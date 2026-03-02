<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-6 py-3 bg-sage border border-transparent rounded-xl font-semibold text-sm text-white transition-all hover:bg-sage/90 focus:bg-sage/90 active:bg-sage focus:outline-none focus:ring-2 focus:ring-sage focus:ring-offset-2 shadow-md shadow-sage/20 hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
