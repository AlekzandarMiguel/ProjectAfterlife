@extends('layouts.admin', ['title' => 'Recovery Monitoring — Project Afterlife', 'header' => 'Recovery Workspaces Monitoring'])

@section('content')
<div class="space-y-6">
    <div class="pb-4 border-b border-slate-800">
        <h2 class="text-lg font-bold text-white tracking-tight">Recovery Health & Inactivity Monitor</h2>
        <p class="text-xs text-slate-400 mt-0.5">Track developer activity on adopted projects, identify stale workspaces, and manage inactive or re-abandoned software.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-mono text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                        <x-status-badge :status="$project->status" />
                    </div>
                    <h3 class="text-base font-semibold text-white">{{ $project->title }}</h3>
                    <div class="mt-2 text-xs text-slate-400">
                        Owner: <span class="text-slate-200 font-medium">{{ $project->owner->name }}</span>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $progress = $project->recovery_progress;
                        $daysInactive = $project->last_activity_at ? (int) $project->last_activity_at->diffInDays(now()) : 999;
                    @endphp
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-1 font-mono">
                            <span>Progress</span>
                            <span class="font-bold text-white">{{ $progress }}%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono mt-1">
                            Last Active: {{ $project->last_activity_at?->diffForHumans() }} ({{ $daysInactive }} days ago)
                        </div>
                    </div>
                </div>

                <!-- Admin Action Controls -->
                <div class="pt-4 border-t border-slate-800 space-y-2">
                    @if($daysInactive > 14 && $project->isUnderRecovery())
                        <form action="{{ route('admin.recovery.warning', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded bg-amber-600/20 border border-amber-500/30 px-3 py-1.5 text-xs text-amber-300 hover:bg-amber-600/30 transition">
                                Send Inactivity Warning
                            </button>
                        </form>
                    @endif

                    @if($project->isUnderRecovery())
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('admin.recovery.inactive', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded bg-slate-800 border border-slate-700 px-2 py-1 text-[11px] text-slate-300 hover:bg-slate-700">
                                    Mark Inactive
                                </button>
                            </form>
                            <form action="{{ route('admin.recovery.reabandon', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded bg-rose-950/50 border border-rose-800 px-2 py-1 text-[11px] text-rose-300 hover:bg-rose-900/50">
                                    Re-Abandon
                                </button>
                            </form>
                        </div>
                    @elseif($project->status->value === 'ABANDONED_AGAIN' || $project->status->value === 'INACTIVE')
                        <form action="{{ route('admin.recovery.reopen', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                                Reopen for Adoption
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">{{ $projects->links() }}</div>
</div>
@endsection
