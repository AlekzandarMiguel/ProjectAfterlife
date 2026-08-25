@extends('layouts.guest', ['title' => $project->title . ' — Project Afterlife'])

@section('content')
<div class="py-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('explore.index') }}" class="text-xs text-slate-400 hover:text-white transition flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                <span>Back to Explorer</span>
            </a>
            <span class="text-slate-600">•</span>
            <span class="text-xs font-mono text-slate-400">{{ $project->category->name ?? 'General' }}</span>
        </div>
        <x-status-badge :status="$project->status" />
    </div>

    <!-- Title Header -->
    <div class="mt-6 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">{{ $project->title }}</h1>
            <p class="mt-2 text-sm text-slate-400 max-w-3xl leading-relaxed">{{ $project->short_description }}</p>
        </div>

        <!-- Action / Adoption Callout -->
        <div class="shrink-0 flex flex-col sm:flex-row lg:flex-col gap-3">
            @if($project->isAvailable())
                @auth
                    @if($canAdopt)
                        <a href="{{ route('user.adoptions.create', $project) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            <span>Adopt This Project</span>
                        </a>
                    @elseif($userHasPendingAdoption)
                        <div class="rounded-lg bg-amber-950/60 border border-amber-500/40 px-4 py-2.5 text-xs text-amber-300 font-medium text-center">
                            Your adoption request is under admin review.
                        </div>
                    @else
                        <div class="rounded-lg bg-slate-900 border border-slate-800 px-4 py-2.5 text-xs text-slate-400 text-center">
                            You currently own this project.
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm">
                        <span>Sign In to Adopt</span>
                    </a>
                @endauth
            @elseif($project->isUnderRecovery())
                <div class="rounded-lg bg-sky-950/50 border border-sky-500/30 px-4 py-3 text-xs text-sky-300">
                    <div class="font-semibold flex items-center gap-1.5 mb-1">
                        <span class="h-2 w-2 rounded-full bg-sky-400"></span>
                        <span>Under Active Recovery</span>
                    </div>
                    <div class="text-[11px] text-slate-400">Progress: {{ $project->recovery_progress }}% ({{ $project->completed_tasks_count }} of {{ $project->total_tasks_count }} tasks)</div>
                </div>
            @elseif($project->isResurrected())
                <div class="rounded-lg bg-purple-950/50 border border-purple-500/30 px-4 py-3 text-xs text-purple-300">
                    <div class="font-semibold flex items-center gap-1.5 mb-1">
                        <span>🏆 Resurrected Software</span>
                    </div>
                    <div class="text-[11px] text-slate-400">Successfully recovered and verified by Admin.</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content & Metadata Columns -->
    <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Full Description, Files, Recovery Roadmap -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Full Description -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6">
                <h2 class="text-sm font-semibold text-white uppercase tracking-wider font-mono mb-4">Project Overview & Architecture</h2>
                <div class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed whitespace-pre-line">
                    {{ $project->description }}
                </div>
            </div>

            <!-- Abandonment Reason Card -->
            <div class="rounded-xl border border-rose-950/60 bg-rose-950/20 p-6">
                <h2 class="text-sm font-semibold text-rose-300 uppercase tracking-wider font-mono mb-2 flex items-center gap-2">
                    <svg class="h-4 w-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>Reason for Abandonment</span>
                </h2>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "{{ $project->reason_for_abandonment }}"
                </p>
                <div class="mt-4 flex flex-wrap gap-4 text-[11px] text-slate-400 font-mono">
                    @if($project->original_development_date)
                        <div>Original Dev: <span class="text-slate-300">{{ $project->original_development_date->format('M Y') }}</span></div>
                    @endif
                    @if($project->last_development_date)
                        <div>Last Active Dev: <span class="text-slate-300">{{ $project->last_development_date->format('M Y') }}</span></div>
                    @endif
                </div>
            </div>

            <!-- Project Files (Authorized Downloads) -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6">
                <h2 class="text-sm font-semibold text-white uppercase tracking-wider font-mono mb-4">Project Files & Archives</h2>
                @if($project->files->count() > 0)
                    <div class="divide-y divide-slate-800">
                        @foreach($project->files as $file)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded bg-slate-800 text-slate-400 font-mono text-xs">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-white">{{ $file->file_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $file->file_type->label() }} • {{ $file->formatted_size }}</div>
                                    </div>
                                </div>
                                @auth
                                    <a href="{{ route('explore.files.download', [$project, $file]) }}" class="rounded-lg bg-slate-800 border border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-200 hover:bg-slate-700 transition flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        <span>Download</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-400">Sign in to download</span>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400">No standalone archive files attached yet.</p>
                @endif
            </div>

            <!-- Project History Timeline -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6">
                <h2 class="text-sm font-semibold text-white uppercase tracking-wider font-mono mb-4">Lifecycle Audit Timeline</h2>
                <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-800">
                    @forelse($project->history as $history)
                        <div class="relative">
                            <div class="absolute -left-6 top-1.5 h-2.5 w-2.5 rounded-full border-2 border-slate-950 bg-emerald-400"></div>
                            <div class="text-xs font-semibold text-white flex items-center gap-2">
                                <span>{{ $history->action }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $history->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ $history->description }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No historical events recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Technical Stack, Ownership Info, Transfer History -->
        <div class="space-y-8">
            <!-- Tech Stack Badges -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-3">Technologies</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($project->technologies as $tech)
                        <div class="rounded-lg bg-slate-950 border border-slate-800 px-3 py-1.5 text-xs text-slate-200">
                            <span class="font-medium">{{ $tech->name }}</span>
                            <span class="text-[10px] text-slate-400 block font-mono">{{ $tech->type->label() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Current Owner Card -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-3">Current Owner</h3>
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full bg-slate-800 ring-1 ring-slate-700" src="{{ $project->owner->avatar_url }}" alt="{{ $project->owner->name }}">
                    <div>
                        <div class="text-xs font-semibold text-white">{{ $project->owner->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">@ {{ $project->owner->username ?? 'user' }}</div>
                    </div>
                </div>
                @if($project->owner->profile?->bio)
                    <p class="mt-3 text-xs text-slate-400 leading-relaxed">{{ $project->owner->profile->bio }}</p>
                @endif
            </div>

            <!-- Original Author Card (Preserved in history) -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-3">Original Uploader / Creator</h3>
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full bg-slate-800 ring-1 ring-slate-700" src="{{ $project->originalOwner->avatar_url }}" alt="{{ $project->originalOwner->name }}">
                    <div>
                        <div class="text-xs font-semibold text-white">{{ $project->originalOwner->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">Original Creator</div>
                    </div>
                </div>
            </div>

            <!-- Ownership Transfer Ledger Records -->
            @if($project->ownershipTransfers->count() > 0)
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5">
                    <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-3">Ownership Transfer Records</h3>
                    <div class="space-y-3">
                        @foreach($project->ownershipTransfers as $trans)
                            <div class="rounded-lg bg-slate-950 border border-slate-800 p-3 text-xs">
                                <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono mb-1.5">
                                    <span>Transferred</span>
                                    <span>{{ $trans->transferred_at->format('M d, Y') }}</span>
                                </div>
                                <div class="text-slate-300">
                                    From <span class="font-medium text-white">{{ $trans->previousOwner->name }}</span> to <span class="font-medium text-emerald-400">{{ $trans->newOwner->name }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1">Approved by Admin: {{ $trans->adminApprover->name }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
