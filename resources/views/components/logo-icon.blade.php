@props(['size' => '32', 'color' => '', 'class' => ''])

@php
    $pixelSize = is_numeric($size) ? $size : '32';
    $version = file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time();
@endphp

<span class="inline-flex items-center justify-center shrink-0 {{ $class }}" style="width: {{ $pixelSize }}px; height: {{ $pixelSize }}px; min-width: {{ $pixelSize }}px; min-height: {{ $pixelSize }}px;">
    <img src="{{ asset('images/logo.png') }}?v={{ $version }}" alt="Project Afterlife" class="w-full h-full object-contain pointer-events-none select-none" style="width: 100%; height: 100%; display: block;" />
</span>
