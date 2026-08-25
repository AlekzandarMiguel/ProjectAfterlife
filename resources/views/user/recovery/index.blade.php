@extends('layouts.app', ['title' => 'Recovery Workspaces — Project Afterlife', 'header' => 'Recovery Workspaces'])

@section('content')
<div class="space-y-6">
    <div class="pb-4 border-b border-slate-800">
        <h2 class="text-lg font-bold text-white tracking-tight">Active Recovery Workspaces</h2>
        <p class="text-xs text-slate-400 mt-0.5">Projects currently under your ownership being repaired and updated.</p>
    </div>

    @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($projects as $project)
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-mono text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                            <x-status-badge :status="$project->status" />
                        </div>
                        <h3 class="text-lg font-bold text-white">{{ $project->title }}</h3>
                        <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $project->short_description }}</p>

                        <!-- Dynamic Progress Calculation -->
                        @php
                            $progress = $project->recovery_progress;
                            $completed = $project->completed_tasks_count;
                            $total = $project->total_tasks_count;
                        @endphp
                        <div class="mt-6">
                            <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5 font-mono">
                                <span>Recovery Progress</span>
                                <span class="font-bold text-white">{{ $progress }}%</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono mt-1">{{ $completed }} of {{ $total }} recovery tasks completed</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Active: {{ $project->last_activity_at?->diffForHumans() }}</span>
                        <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-lg bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                            Enter Workspace &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $projects->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
            <h3 class="text-base font-semibold text-white">No active recovery workspaces</h3>
            <p class="text-xs text-slate-400 mt-1">You do not currently own any projects under active recovery.</p>
            <a href="{{ route('explore.index') }}" class="inline-block mt-4 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                Explore Abandoned Projects to Adopt
            </a>
        </div>
    @endif
</div>
@endsection
