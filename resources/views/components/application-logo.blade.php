@props([
    'size' => '32',
    'showSubtitle' => true,
    'subtitle' => 'Software Preservation',
    'color' => 'text-emerald-600 dark:text-emerald-400',
    'class' => '',
])

<div class="inline-flex items-center gap-3 group select-none {{ $class }}">
    <div class="shrink-0 transition-transform duration-200 group-hover:scale-105">
        <x-logo-icon :size="$size" :color="$color" />
    </div>
    <div class="flex flex-col text-left min-w-0">
        <span class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors leading-none truncate">
            Project Afterlife
        </span>
        @if($showSubtitle)
            <span class="text-[9.5px] uppercase tracking-[0.14em] font-mono text-emerald-600 dark:text-emerald-400 font-bold whitespace-nowrap mt-1">
                {{ $subtitle }}
            </span>
        @endif
    </div>
</div>
