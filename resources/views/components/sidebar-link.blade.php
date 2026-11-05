@props(['active' => false, 'icon' => null])

@php
    $classes = $active
        ? 'bg-white/15 text-white font-semibold'
        : 'text-green-50/75 hover:bg-white/10 hover:text-white';

    $iconClasses = $active
        ? 'text-white'
        : 'text-green-100/60 group-hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => "group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors $classes"]) }}>
    @if ($active)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-white"></span>
    @endif

    <span class="w-5 flex items-center justify-center flex-shrink-0 {{ $iconClasses }}">
        {{ $icon }}
    </span>

    <span class="truncate">{{ $slot }}</span>
</a>
