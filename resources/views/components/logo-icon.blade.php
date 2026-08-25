@props(['size' => '32', 'color' => '', 'class' => ''])

@php
    $pixelSize = is_numeric($size) ? $size : '32';
@endphp

<span class="inline-flex items-center justify-center shrink-0 {{ $class }}" style="width: {{ $pixelSize }}px; height: {{ $pixelSize }}px; min-width: {{ $pixelSize }}px; min-height: {{ $pixelSize }}px;">
    <img src="{{ asset('images/logo.png') }}" alt="Project Afterlife" class="w-full h-full object-contain filter drop-shadow-[0_4px_12px_rgba(16,185,129,0.3)] pointer-events-none select-none" style="width: 100%; height: 100%; display: block;" />
</span>
