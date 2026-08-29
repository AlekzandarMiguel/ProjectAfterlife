@extends('layouts.app', ['title' => 'Dashboard — Project Afterlife', 'header' => 'Developer Workspace'])

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Banner -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition theme-interactive-card">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage your uploaded software, active recovery workspaces, and adoption proposals.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Upload Abandoned Project</span>
            </a>
            <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white transition shadow-xs">
                <span>Browse to Adopt</span>
            </a>
        </div>
    </div>

    <!-- 5 Clean Telemetry Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card">
            <div class="text-[11px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400">My Uploaded Projects</div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $stats['my_uploaded_count'] }}</div>
            <a href="{{ route('user.projects.index', ['tab' => 'uploaded']) }}" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline mt-2 inline-block">View uploads &rarr;</a>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card">
            <div class="text-[11px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400">Adopted Projects</div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $stats['my_adopted_count'] }}</div>
            <a href="{{ route('user.projects.index', ['tab' => 'adopted']) }}" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline mt-2 inline-block">View adopted &rarr;</a>
        </div>
        <div class="rounded-2xl border border-amber-200 dark:border-slate-800 bg-amber-50/50 dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card">
            <div class="text-[11px] font-mono font-bold uppercase text-amber-800 dark:text-amber-400">Pending Requests</div>
            <div class="text-2xl font-bold text-amber-700 dark:text-amber-400 font-mono mt-1">{{ $stats['pending_requests_count'] }}</div>
            <a href="{{ route('user.adoptions.index') }}" class="text-[11px] font-bold text-amber-700 dark:text-amber-400 hover:underline mt-2 inline-block">Track requests &rarr;</a>
        </div>
        <div class="rounded-2xl border border-sky-200 dark:border-slate-800 bg-sky-50/50 dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card">
            <div class="text-[11px] font-mono font-bold uppercase text-sky-800 dark:text-sky-400">Active Recoveries</div>
            <div class="text-2xl font-bold text-sky-700 dark:text-sky-400 font-mono mt-1">{{ $stats['active_recoveries_count'] }}</div>
            <a href="{{ route('user.recovery.index') }}" class="text-[11px] font-bold text-sky-700 dark:text-sky-400 hover:underline mt-2 inline-block">Open workspaces &rarr;</a>
        </div>
        <div class="rounded-2xl border border-purple-200 dark:border-slate-800 bg-purple-50/50 dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card col-span-2 lg:col-span-1">
            <div class="text-[11px] font-mono font-bold uppercase text-purple-800 dark:text-purple-400">Resurrected Projects</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-400 font-mono mt-1">{{ $stats['resurrected_count'] }}</div>
            <span class="text-[10px] text-purple-600 dark:text-purple-400 font-medium block mt-2">Certified revived</span>
        </div>
    </div>

    <!-- Active Recovery Workspaces Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight uppercase font-mono">My Active Recovery Projects</h2>
                <a href="{{ route('user.recovery.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">View All &rarr;</a>
            </div>

            @if($recoveryProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recoveryProjects as $project)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-5 shadow-xs theme-interactive-card">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $project->category->name ?? 'Project' }}</span>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white mt-0.5">{{ $project->title }}</h3>
                                </div>
                                <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-500 transition self-start sm:self-auto shadow-xs">
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
                                <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 mb-1.5 font-mono">
                                    <span class="font-medium">Recovery Progress</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $progress }}% ({{ $completed }} of {{ $total }} tasks)</span>
                                </div>
                                <div class="h-2.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden border border-slate-200 dark:border-slate-700/50">
                                    <div class="h-full bg-emerald-500 transition-all duration-500 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 p-8 text-center shadow-xs">
                    <p class="text-xs text-slate-500 dark:text-slate-400">You do not have any active recovery workspaces at this time.</p>
                    <a href="{{ route('explore.index') }}" class="inline-block mt-3 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                        Explore projects available for adoption &rarr;
                    </a>
                </div>
            @endif

            <!-- Tasks Assigned / Due Soon -->
            @if($pendingTasks->count() > 0)
                <div class="pt-4 space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight uppercase font-mono">Pending Checklist Tasks</h2>
                    <div class="divide-y divide-slate-200 dark:divide-slate-800 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 rounded-2xl overflow-hidden shadow-xs">
                        @foreach($pendingTasks as $task)
                            <div class="p-3.5 flex items-center justify-between gap-3 text-xs hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <div class="flex items-center gap-2.5">
                                    <span class="h-2 w-2 rounded-full bg-amber-500 shrink-0"></span>
                                    <span class="text-slate-900 dark:text-white font-semibold">{{ $task->title }}</span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">({{ $task->project->title }})</span>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400">{{ $task->due_date?->format('M d') ?? 'No date' }}</span>
                                    <a href="{{ route('user.recovery.workspace', $task->project) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline font-mono text-[11px] font-bold">Workspace &rarr;</a>
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
                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight uppercase font-mono mb-4">Adoption Proposals</h2>
                @if($pendingAdoptions->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingAdoptions as $ad)
                            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-4 shadow-xs theme-interactive-card">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $ad->project->title }}</span>
                                    <span class="text-[10px] rounded-md bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/50 px-2 py-0.5 font-bold font-mono">Pending</span>
                                </div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-1">Submitted: {{ $ad->created_at->diffForHumans() }}</div>
                                <a href="{{ route('user.adoptions.show', $ad) }}" class="mt-2 inline-block text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">View application details &rarr;</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 p-5 text-xs text-slate-500 dark:text-slate-400 text-center shadow-xs">
                        No pending adoption requests.
                    </div>
                @endif
            </div>

            <!-- Recent Activity Timeline -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight uppercase font-mono mb-4">Recent Project Events</h2>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-4 space-y-4 shadow-xs">
                    @forelse($recentActivity as $act)
                        <div class="text-xs pb-3 border-b border-slate-200 dark:border-slate-800/80 last:border-0 last:pb-0">
                            <div class="text-slate-900 dark:text-slate-200 font-bold">{{ $act->action }}</div>
                            <div class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5">{{ $act->description }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-1">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 dark:text-slate-400 text-center py-2">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
