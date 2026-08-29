@props(['size' => '32', 'color' => 'text-emerald-400', 'class' => ''])

@php
    $pixelSize = is_numeric($size) ? $size : '32';
@endphp

<span class="inline-flex items-center justify-center shrink-0 {{ $color }} {{ $class }}" style="width: {{ $pixelSize }}px; height: {{ $pixelSize }}px; min-width: {{ $pixelSize }}px; min-height: {{ $pixelSize }}px;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="width: 100%; height: 100%; display: block;">
        <!-- Outer Gentle Caring Arcs / Protective Hands -->
        <path d="M 38 12 C 68 8 90 28 90 50 C 90 70 74 88 52 90" stroke-width="3.5" />
        <path d="M 62 88 C 32 92 10 72 10 50 C 10 30 26 12 48 10" stroke-width="3.5" />
        
        <!-- Gentle Leaf Accent on Outer Arc -->
        <path d="M 17 38 C 12 45 14 55 22 58 C 22 48 19 40 17 38 Z" stroke-width="2.5" />

        <!-- Left Friendly Code Bracket -->
        <path d="M 35 34 C 29 34 25 38 25 44 C 25 48 23 50 19 50 C 23 50 25 52 25 56 C 25 62 29 66 35 66" stroke-width="3" />

        <!-- Right Friendly Code Bracket -->
        <path d="M 65 34 C 71 34 75 38 75 44 C 75 48 77 50 81 50 C 77 50 75 52 75 56 C 75 62 71 66 65 66" stroke-width="3" />

        <!-- Center Sprouting Seedling (Software rebirth & second life) -->
        <path d="M 48 70 C 48 58 50 48 50 36" stroke-width="3" />
        <path d="M 49 52 C 40 52 36 45 38 38 C 45 38 49 43 49 52 Z" stroke-width="2.5" />
        <path d="M 50 44 C 59 44 63 37 61 30 C 54 30 50 35 50 44 Z" stroke-width="2.5" />
        <circle cx="56" cy="56" r="2" fill="currentColor" stroke="none" />
    </svg>
</span>
