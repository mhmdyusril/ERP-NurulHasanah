@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-sage focus:ring-sage rounded-xl shadow-sm transition-colors']) !!}>
