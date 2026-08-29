@extends('layouts.app', ['title' => 'My Watchlist — Project Afterlife', 'header' => 'My Watchlist'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Preservation Watchlist</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Repositories you are monitoring for recovery updates, releases, and adoption transitions.</p>
        </div>

        <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-xs">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Explore More Repositories</span>
        </a>
    </div>

    @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($projects as $project)
                <div class="group relative rounded-2xl border border-slate-200 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/60 p-5 flex flex-col justify-between hover:border-emerald-500/40 hover:shadow-lg transition-all duration-200">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $project->category->name ?? 'General' }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $project->status->badgeClasses() }}">
                                {{ $project->status->label() }}
                            </span>
                        </div>

                        <div>
                            <a href="{{ route('explore.show', $project) }}" class="block">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition leading-snug">
                                    {{ $project->title }}
                                </h3>
                            </a>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $project->short_description }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-slate-400 font-mono">By {{ $project->owner->name ?? 'Unknown' }}</span>
                        
                        <div class="flex items-center gap-2">
                            <form action="{{ route('user.bookmarks.toggle', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-800 text-amber-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 hover:text-rose-600 hover:border-rose-300 transition cursor-pointer" title="Remove from Watchlist">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                </button>
                            </form>

                            <a href="{{ route('explore.show', $project) }}" class="px-3 py-1.5 rounded-lg bg-slate-900 dark:bg-slate-800 text-xs font-bold text-white hover:bg-emerald-600 transition">
                                View &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $projects->links() }}
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-12 text-center max-w-lg mx-auto space-y-4">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Your Watchlist is Empty</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                You haven't bookmarked any projects yet. Click the Watch button on any abandoned repository to track its recovery progress.
            </p>
            <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-xs">
                Browse Preservation Registry
            </a>
        </div>
    @endif
</div>
@endsection
