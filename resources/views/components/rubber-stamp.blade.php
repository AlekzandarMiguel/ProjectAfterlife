@props([
    'text' => 'VERIFIED',
    'color' => '#dc2626',
    'size' => 'w-28 h-12 sm:w-32 sm:h-14',
    'rotate' => '-rotate-12',
    'class' => '',
])

@php
    $len = strlen($text);
    $fontSize = $len > 8 ? '28' : ($len > 6 ? '34' : '36');
    $letterSpacing = $len > 8 ? '2' : '4';
    $filterId = 'stamp-rough-' . preg_replace('/[^a-z0-9]/', '', strtolower($text));
@endphp

<div class="relative inline-flex items-center justify-center select-none transform {{ $rotate }} {{ $size }} {{ $class }}">
    <svg class="w-full h-full drop-shadow-xs" viewBox="0 0 260 110" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <filter id="{{ $filterId }}" x="0%" y="0%" width="100%" height="100%">
                <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="3" result="noise" />
                <feDisplacementMap in="SourceGraphic" in2="noise" scale="2" xChannelSelector="R" yChannelSelector="G" />
            </filter>
        </defs>

        <g filter="url(#{{ $filterId }})">
            <!-- Outer Rounded Rectangular Stamp Border -->
            <rect x="8" y="8" width="244" height="94" rx="14" stroke="{{ $color }}" stroke-width="6" fill="none" opacity="0.9" />
            
            <!-- Inner Fine Border Line -->
            <rect x="16" y="16" width="228" height="78" rx="8" stroke="{{ $color }}" stroke-width="2" stroke-dasharray="6 3" fill="none" opacity="0.65" />

            <!-- Main Stamp Text -->
            <text x="130" y="66" font-family="'Impact', 'Arial Black', sans-serif" font-size="{{ $fontSize }}" font-weight="900" fill="{{ $color }}" text-anchor="middle" letter-spacing="{{ $letterSpacing }}" opacity="0.95">
                {{ $text }}
            </text>
        </g>
    </svg>
</div>
