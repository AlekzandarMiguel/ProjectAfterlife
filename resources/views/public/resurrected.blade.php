@extends('layouts.guest', ['title' => 'Resurrected Projects Hall of Fame — Project Afterlife'])

@section('content')
<div class="py-12 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-12">
    <!-- Header Banner -->
    <div class="border-b border-slate-200 dark:border-slate-800 pb-8 text-center max-w-3xl mx-auto space-y-3">
        <div class="inline-flex items-center gap-2 rounded-full border border-purple-300 dark:border-purple-800/80 bg-purple-50 dark:bg-purple-950/60 px-3.5 py-1 text-xs font-mono font-bold text-purple-800 dark:text-purple-300">
            <span>RESURRECTION HALL OF FAME</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl tracking-tight">Successfully Revived Software</h1>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto">
            These software repositories were once abandoned by their original creators, adopted by dedicated maintainers, repaired, upgraded, and certified as fully active and preserved.
        </p>
    </div>

    @if($resurrectedProjects->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($resurrectedProjects as $project)
                @php
                    $durationDays = max(1, $project->created_at->diffInDays($project->resurrected_at ?? now()));
                    $completedTasksCount = $project->recoveryTasks()->where('is_completed', true)->count();
                @endphp
                <div class="rounded-3xl border-2 border-purple-500/30 bg-white dark:bg-slate-900 p-6 sm:p-8 flex flex-col justify-between shadow-xs theme-interactive-card space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-lg bg-purple-100 dark:bg-purple-950/80 border border-purple-300 dark:border-purple-700 px-3 py-1 text-xs font-mono font-bold text-purple-800 dark:text-purple-300">
                                RESURRECTED & CERTIFIED
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                                Revived: {{ $project->resurrected_at?->format('M d, Y') ?? 'Certified' }}
                            </span>
                        </div>

                        <div>
                            <a href="{{ route('explore.show', $project) }}" class="group block">
                                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">{{ $project->title }}</h2>
                            </a>
                            <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $project->short_description }}</p>
                        </div>

                        <!-- Metrics Ribbon -->
                        <div class="grid grid-cols-3 gap-2 text-center p-3 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-mono">
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Recovery Time</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $durationDays }} Days</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Tasks Repaired</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $completedTasksCount }} Items</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Release</span>
                                <span class="font-bold text-purple-600 dark:text-purple-400">{{ $project->latestVersion->version_number ?? 'v1.0.0' }}</span>
                            </div>
                        </div>

                        <!-- Chain of Custody Box -->
                        <div class="grid grid-cols-2 gap-3 bg-slate-50/80 dark:bg-slate-950/80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs">
                            <div class="space-y-1">
                                <div class="text-[10px] uppercase font-mono tracking-wider font-bold text-slate-500 dark:text-slate-400">Original Author</div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ $project->originalOwner->name ?? 'Original Creator' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Submitted {{ $project->created_at->format('M Y') }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-[10px] uppercase font-mono tracking-wider font-bold text-emerald-600 dark:text-emerald-400">Lead Resurrector</div>
                                <div class="font-bold text-emerald-700 dark:text-emerald-300">{{ $project->owner->name ?? 'Maintainer' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Current Steward</div>
                            </div>
                        </div>

                        <!-- Completion Statement -->
                        @if($project->latestFinalReview)
                            <div class="text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-1">
                                <span class="text-[10px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400 block">Certification Review Statement</span>
                                <p class="line-clamp-2 italic">"{{ $project->latestFinalReview->completion_summary }}"</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Actions & Tags -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($project->technologies->take(3) as $tech)
                                <span class="rounded-lg bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-[10px] font-mono font-medium text-slate-700 dark:text-slate-300">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('explore.certificate', $project) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-bold text-slate-700 dark:text-slate-300 hover:bg-emerald-600 hover:text-white transition">
                                <span>Certificate</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                            <a href="{{ route('explore.show', $project) }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-xl bg-purple-600 text-xs font-mono font-bold text-white hover:bg-purple-500 transition shadow-xs">
                                <span>View Repository</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $resurrectedProjects->links() }}
        </div>
    @else
        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-12 text-center max-w-xl mx-auto space-y-4">
            <h3 class="text-base font-bold text-slate-900 dark:text-white font-mono">No Projects Resurrected Yet</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Be the first developer to adopt an abandoned codebase and guide it through full recovery to earn a permanent spot in the Hall of Fame.
            </p>
            <div class="pt-2">
                <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-xs">
                    <span>Browse Abandoned Projects</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
