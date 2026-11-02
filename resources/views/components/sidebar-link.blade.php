@php
$classes = $active
    ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-white'
    : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors';

$iconColor = $active ? 'text-white' : 'text-gray-400 group-hover:text-gray-600';
@endphp

<div class="py-1">
    <a {{ $attributes->merge(['class' => $classes]) }}
        @if($active) style="background: #1a6b3a;" @endif>
        @if(isset($icon))
            <span class="{{ $iconColor }} transition-colors shrink-0">
                {{ $icon }}
            </span>
        @endif
        <span>{{ $slot }}</span>
    </a>
</div>
