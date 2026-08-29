@props(['status'])

@php
    $classes = $status->badgeClasses();
    $label = $status->label();
@endphp

<span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-medium {{ $classes }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
    <span>{{ $label }}</span>
</span>
