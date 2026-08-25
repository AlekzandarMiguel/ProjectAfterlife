@extends('layouts.app', ['title' => 'Dashboard — Project Afterlife', 'header' => 'Developer Workspace'])

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Banner -->
    <div class="rounded-2xl border border-slate-800 bg-gradient-to-r from-slate-900 via-slate-900 to-emerald-950/30 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-400 mt-1">Manage your uploaded software, active recovery workspaces, and adoption proposals.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Upload Abandoned Project</span>
            </a>
            <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition">
                <span>Browse to Adopt</span>
            </a>
        </div>
    </div>

    <!-- 5 Clean Telemetry Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-slate-400">My Uploaded Projects</div>
            <div class="text-2xl font-bold text-white font-mono mt-1">{{ $stats['my_uploaded_count'] }}</div>
            <a href="{{ route('user.projects.index', ['tab' => 'uploaded']) }}" class="text-[10px] text-emerald-400 hover:underline mt-2 inline-block">View uploads &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-slate-400">Adopted Projects</div>
            <div class="text-2xl font-bold text-white font-mono mt-1">{{ $stats['my_adopted_count'] }}</div>
            <a href="{{ route('user.projects.index', ['tab' => 'adopted']) }}" class="text-[10px] text-emerald-400 hover:underline mt-2 inline-block">View adopted &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-slate-400">Pending Requests</div>
            <div class="text-2xl font-bold text-amber-400 font-mono mt-1">{{ $stats['pending_requests_count'] }}</div>
            <a href="{{ route('user.adoptions.index') }}" class="text-[10px] text-amber-400 hover:underline mt-2 inline-block">Track requests &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-slate-400">Active Recoveries</div>
            <div class="text-2xl font-bold text-sky-400 font-mono mt-1">{{ $stats['active_recoveries_count'] }}</div>
            <a href="{{ route('user.recovery.index') }}" class="text-[10px] text-sky-400 hover:underline mt-2 inline-block">Open workspaces &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4 col-span-2 lg:col-span-1">
            <div class="text-[11px] font-mono text-slate-400">Resurrected Projects</div>
            <div class="text-2xl font-bold text-purple-400 font-mono mt-1">{{ $stats['resurrected_count'] }}</div>
            <span class="text-[10px] text-purple-400 block mt-2">Certified revived</span>
        </div>
    </div>

    <!-- Active Recovery Workspaces Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono">My Active Recovery Projects</h3>
                <a href="{{ route('user.recovery.index') }}" class="text-xs text-emerald-400 hover:underline">View All</a>
            </div>

            @if($recoveryProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recoveryProjects as $project)
                        <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-5 hover:border-slate-700 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider">{{ $project->category->name ?? 'Project' }}</span>
                                    <h4 class="text-base font-semibold text-white mt-0.5">{{ $project->title }}</h4>
                                </div>
                                <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500 transition self-start sm:self-auto">
                                    Open Workspace
                                </a>
                            </div>

                            <!-- Live Progress Bar -->
                            @php
                                $progress = $project->recovery_progress;
                                $total = $project->total_tasks_count;
                                $completed = $project->completed_tasks_count;
                            @endphp
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5 font-mono">
                                    <span>Recovery Progress</span>
                                    <span class="font-bold text-white">{{ $progress }}% ({{ $completed }} of {{ $total }} tasks)</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full bg-emerald-500 transition-all duration-500 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-8 text-center">
                    <p class="text-xs text-slate-400">You do not have any active recovery workspaces at this time.</p>
                    <a href="{{ route('explore.index') }}" class="inline-block mt-3 text-xs font-semibold text-emerald-400 hover:underline">
                        Explore projects available for adoption &rarr;
                    </a>
                </div>
            @endif

            <!-- Tasks Assigned / Due Soon -->
            @if($pendingTasks->count() > 0)
                <div class="pt-4">
                    <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono mb-4">Pending Checklist Tasks</h3>
                    <div class="divide-y divide-slate-800 border border-slate-800 bg-slate-900/30 rounded-xl overflow-hidden">
                        @foreach($pendingTasks as $task)
                            <div class="p-3.5 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                    <span class="text-white font-medium">{{ $task->title }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">({{ $task->project->title }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-mono text-slate-400">{{ $task->due_date?->format('M d') ?? 'No date' }}</span>
                                    <a href="{{ route('user.recovery.workspace', $task->project) }}" class="text-emerald-400 hover:underline font-mono text-[11px]">Workspace &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Pending Adoptions & Recent Activity Stream -->
        <div class="space-y-6">
            <div>
                <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono mb-4">Adoption Proposals</h3>
                @if($pendingAdoptions->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingAdoptions as $ad)
                            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-semibold text-white truncate">{{ $ad->project->title }}</span>
                                    <span class="text-[10px] rounded bg-amber-500/10 text-amber-400 px-1.5 py-0.5 font-mono">Pending</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">Submitted: {{ $ad->created_at->diffForHumans() }}</div>
                                <a href="{{ route('user.adoptions.show', $ad) }}" class="mt-2 inline-block text-xs text-emerald-400 hover:underline">View application details &rarr;</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-5 text-xs text-slate-400 text-center">
                        No pending adoption requests.
                    </div>
                @endif
            </div>

            <!-- Recent Activity Timeline -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono mb-4">Recent Project Events</h3>
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4 space-y-4">
                    @forelse($recentActivity as $act)
                        <div class="text-xs pb-3 border-b border-slate-800/80 last:border-0 last:pb-0">
                            <div class="text-slate-300 font-medium">{{ $act->action }}</div>
                            <div class="text-slate-400 text-[11px] mt-0.5">{{ $act->description }}</div>
                            <div class="text-[10px] text-slate-400 font-mono mt-1">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 text-center py-2">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
