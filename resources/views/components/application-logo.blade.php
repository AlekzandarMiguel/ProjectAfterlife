@props(['size' => '32', 'showSubtitle' => true, 'color' => 'text-emerald-600 dark:text-emerald-400'])

<div class="inline-flex items-center gap-3 group">
    <x-logo-icon :size="$size" :color="$color" class="transition-transform duration-200 group-hover:scale-105" />
    <div class="flex flex-col">
        <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition leading-tight">Project Afterlife</span>
        @if($showSubtitle)
            <span class="text-[10px] uppercase tracking-wider font-mono text-emerald-600 dark:text-emerald-400 font-medium">Software Revival & Preservation</span>
        @endif
    </div>
</div>
