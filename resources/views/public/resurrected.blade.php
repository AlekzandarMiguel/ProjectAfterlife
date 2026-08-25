@extends('layouts.guest', ['title' => 'Resurrected Projects Hall of Fame — Project Afterlife'])

@section('content')
<div class="py-12 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="border-b border-slate-800 pb-8 mb-12 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 rounded-full border border-purple-500/30 bg-purple-500/10 px-3 py-1 text-xs font-medium text-purple-400 mb-4">
            <span>🏆 Resurrection Hall of Fame</span>
        </div>
        <h1 class="text-3xl font-bold text-white sm:text-4xl">Successfully Resurrected Projects</h1>
        <p class="mt-3 text-sm text-slate-400 leading-relaxed">
            These software projects were abandoned by their original creators, adopted by dedicated developers, thoroughly repaired and completed, and certified as RESURRECTED.
        </p>
    </div>

    @if($resurrectedProjects->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($resurrectedProjects as $project)
                <div class="rounded-2xl border border-purple-500/30 bg-slate-900/60 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="rounded-md bg-purple-500/20 border border-purple-500/40 px-2.5 py-1 text-xs font-mono font-bold text-purple-300">RESURRECTED</span>
                            <span class="text-xs text-slate-400 font-mono">Revived: {{ $project->resurrected_at?->format('M d, Y') }}</span>
                        </div>

                        <a href="{{ route('explore.show', $project) }}" class="group">
                            <h2 class="text-xl font-bold text-white group-hover:text-purple-400 transition">{{ $project->title }}</h2>
                        </a>
                        <p class="mt-2 text-xs text-slate-300 leading-relaxed">{{ $project->short_description }}</p>

                        <!-- Side-by-side Author and Resurrector -->
                        <div class="mt-6 grid grid-cols-2 gap-3 bg-slate-950/80 p-4 rounded-xl border border-slate-800">
                            <div>
                                <div class="text-[10px] uppercase font-mono tracking-wider text-slate-400">Original Author</div>
                                <div class="mt-1 font-semibold text-xs text-slate-200">{{ $project->originalOwner->name }}</div>
                                <div class="text-[10px] text-slate-400">Abandoned in {{ $project->last_development_date?->format('Y') ?? 'Past' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase font-mono tracking-wider text-emerald-400 font-semibold">Resurrector / Owner</div>
                                <div class="mt-1 font-semibold text-xs text-emerald-300">{{ $project->owner->name }}</div>
                                <div class="text-[10px] text-slate-400">Adopted & Completed</div>
                            </div>
                        </div>

                        <!-- Completed Highlights -->
                        @if($project->latestFinalReview)
                            <div class="mt-4 text-xs text-slate-400 bg-slate-950/40 p-3 rounded-lg border border-slate-800/80">
                                <div class="font-semibold text-slate-300 text-[11px] mb-1">Final Recovery Summary:</div>
                                <p class="line-clamp-2">{{ $project->latestFinalReview->completion_summary }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($project->technologies->take(3) as $tech)
                                <span class="rounded bg-slate-800 px-2 py-0.5 text-[10px] font-mono text-slate-300">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('explore.show', $project) }}" class="text-xs font-semibold text-purple-400 hover:text-purple-300 transition">
                            Explore Full Project &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-8">
            {{ $resurrectedProjects->links() }}
        </div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center max-w-md mx-auto">
            <h3 class="text-base font-semibold text-white">No resurrected projects yet</h3>
            <p class="mt-2 text-xs text-slate-400">Active recoveries are underway. Certified resurrected software will appear here once approved by administrators.</p>
        </div>
    @endif
</div>
@endsection
