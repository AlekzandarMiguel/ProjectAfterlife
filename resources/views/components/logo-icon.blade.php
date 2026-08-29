@props(['size' => '32', 'color' => '', 'class' => '', 'forceWhite' => false, 'forceBlack' => false])

@php
    $pixelSize = is_numeric($size) ? $size : '32';
    $version = file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time();
    $filterClass = $forceWhite 
        ? 'brightness-100' 
        : ($forceBlack ? 'brightness-0' : 'brightness-0 dark:brightness-100');
@endphp

<span class="inline-flex items-center justify-center shrink-0 {{ $class }}" style="width: {{ $pixelSize }}px; height: {{ $pixelSize }}px; min-width: {{ $pixelSize }}px; min-height: {{ $pixelSize }}px;">
    <img src="{{ asset('images/logo.png') }}?v={{ $version }}" 
         alt="Project Afterlife" 
         class="w-full h-full object-contain pointer-events-none select-none {{ $filterClass }} transition-all duration-200" 
         style="width: 100%; height: 100%; display: block;" />
</span>
