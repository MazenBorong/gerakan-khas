@props(['type' => 'button', 'variant' => 'primary'])
@php
    $base = 'gk-btn inline-flex items-center justify-center rounded-md text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50';
    $v = $variant === 'ghost' ? 'gk-btn--ghost' : 'gk-btn--primary';
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base $v"]) }}>
    {{ $slot }}
</button>
