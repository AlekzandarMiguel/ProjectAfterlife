@extends('layouts.app', ['title' => 'Recovery: ' . $project->title, 'header' => 'Recovery Workspace'])

@section('content')
<div class="space-y-8" x-data="{ openTaskModal: false, openUpdateModal: false, openRelinquishModal: false }">
    <!-- Top Workspace Header -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-mono text-slate-500 dark:text-slate-400">{{ $project->category->name ?? 'Project' }}</span>
                    <x-status-badge :status="$project->status" />
                </div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $project->title }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-3xl leading-relaxed">{{ $project->short_description }}</p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button type="button" @click="openTaskModal = true" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Add Task</span>
                </button>
                <button type="button" @click="openUpdateModal = true" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3.5 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-700 transition cursor-pointer">
                    Post Note
                </button>
                <a href="{{ route('user.versions.index', $project) }}" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-3.5 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-700 transition">
                    Versions ({{ $project->versions->count() }})
                </a>
                <a href="{{ route('user.final-review.create', $project) }}" class="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition shadow-sm">
                    Submit for Resurrection &rarr;
                </a>
            </div>
        </div>

        <!-- Strict Dynamic Progress Engine Bar -->
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800/80">
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-2 font-mono">
                <span class="font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider text-[10px]">Calculated Recovery Progress:</span>
                <span class="font-bold text-emerald-400 text-sm">{{ $progress }}%</span>
            </div>
            <div class="h-2.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-2">
                <span>{{ $completedTasks }} of {{ $totalTasks }} checklist tasks completed</span>
                <span>Auto-computed from actual task state</span>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left 2 Columns: Core Workflow, Discussion, & Architecture -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- 1. Recovery Checklist & Milestones -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider font-mono flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            <span>Recovery Checklist & Milestones</span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Organized technical roadmap across the 5 preservation phases.</p>
                    </div>
                    <button type="button" @click="openTaskModal = true" class="text-xs font-mono font-semibold text-emerald-500 hover:text-emerald-400 transition cursor-pointer">
                        + Add Milestone
                    </button>
                </div>

                @if($tasks->count() > 0)
                    @php
                        $phases = [
                            'assessment' => 'Phase 1: Assessment',
                            'repair' => 'Phase 2: Repair & Refactor',
                            'development' => 'Phase 3: Development',
                            'testing' => 'Phase 4: Testing & Security',
                            'deployment' => 'Phase 5: Deployment & Release',
                        ];
                    @endphp

                    <div class="space-y-6">
                        @foreach($phases as $phaseKey => $phaseTitle)
                            @php $phaseTasks = $tasks->where('phase.value', $phaseKey); @endphp
                            @if($phaseTasks->count() > 0)
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 p-4 space-y-3">
                                    <div class="text-xs font-mono uppercase tracking-wider text-emerald-500 font-semibold flex items-center justify-between">
                                        <span>{{ $phaseTitle }}</span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $phaseTasks->where('status.value', 'completed')->count() }}/{{ $phaseTasks->count() }} completed</span>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($phaseTasks as $task)
                                            <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 flex items-center justify-between gap-3 hover:border-slate-300 dark:hover:border-slate-700 transition">
                                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                                    <!-- Quick Toggle Status Form -->
                                                    <form action="{{ route('user.recovery.tasks.update', [$project, $task]) }}" method="POST" class="mt-0.5 shrink-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($task->status->value === 'completed')
                                                            <input type="hidden" name="status" value="todo">
                                                            <button type="submit" class="h-4 w-4 rounded bg-emerald-500 text-slate-950 flex items-center justify-center cursor-pointer">
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                            </button>
                                                        @else
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="h-4 w-4 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 hover:border-emerald-500 cursor-pointer"></button>
                                                        @endif
                                                    </form>

                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-xs font-medium truncate {{ $task->status->value === 'completed' ? 'text-slate-400 dark:text-slate-500 line-through' : 'text-slate-900 dark:text-white' }}">
                                                            {{ $task->title }}
                                                        </div>
                                                        @if($task->description)
                                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-relaxed">{{ $task->description }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 shrink-0">
                                                    <span class="rounded px-1.5 py-0.5 text-[10px] font-mono {{ $task->priority->badgeClasses() }}">
                                                        {{ $task->priority->label() }}
                                                    </span>
                                                    <span class="rounded px-1.5 py-0.5 text-[10px] font-mono {{ $task->status->badgeClasses() }}">
                                                        {{ $task->status->label() }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/40 p-8 text-center">
                        <p class="text-xs text-slate-500 dark:text-slate-400">No recovery milestones added yet. Add tasks across the 5 phases to compute your progress.</p>
                        <button type="button" @click="openTaskModal = true" class="mt-3 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition cursor-pointer">
                            Add First Milestone
                        </button>
                    </div>
                @endif
            </div>

            <!-- 2. Collaborative Notes & Discussion Stream -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider font-mono flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            <span>Recovery Collaboration & Discussion</span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Share architecture notes, migration steps, and developer updates.</p>
                    </div>
                </div>

                <!-- Post New Note Form -->
                <form action="{{ route('user.recovery.comments.store', $project) }}" method="POST" class="space-y-3">
                    @csrf
                    <textarea name="comment" rows="3" required placeholder="Add a technical progress update, architecture note, or question..." class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                    
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] text-slate-400 font-mono">Visible to maintainers, original author, and reviewers</div>
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-xs cursor-pointer">
                            <span>Post Workspace Note</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </div>
                </form>

                <!-- Notes List -->
                <div class="space-y-3 pt-2">
                    @forelse($project->recoveryComments ?? [] as $comment)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800/80 bg-slate-50/60 dark:bg-slate-950/60 flex items-start justify-between gap-4">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $comment->author->name ?? 'Developer' }}</span>
                                    @if($comment->user_id === $project->owner_id)
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">Maintainer</span>
                                    @elseif($comment->user_id === $project->original_owner_id)
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-amber-100 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">Original Author</span>
                                    @elseif($comment->author->isAdmin())
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-purple-100 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-800">Admin</span>
                                    @endif
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $comment->comment }}</p>
                            </div>

                            @if(auth()->id() === $comment->user_id || auth()->id() === $project->owner_id || auth()->user()->isAdmin())
                                <form action="{{ route('user.recovery.comments.destroy', [$project, $comment]) }}" method="POST" onsubmit="return confirm('Remove this note?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition text-xs cursor-pointer" title="Delete Note">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 font-mono">
                            No workspace discussion notes yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. Preserved Archive Architecture & Files -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 shadow-sm">
                <x-archive-file-tree :files="$project->files" title="Preserved Archive Architecture & File Tree" />
            </div>

        </div>

        <!-- Right 1 Column: Activity Log, Files, & Stewardship Controls -->
        <div class="space-y-6">
            
            <!-- Quick Actions Panel -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-bold">Workspace Actions</h3>
                <div class="space-y-2">
                    <button type="button" @click="openTaskModal = true" class="w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Add New Milestone Task</span>
                    </button>
                    <button type="button" @click="openUpdateModal = true" class="w-full py-2 px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-semibold transition cursor-pointer">
                        Log Dev Progress Note
                    </button>
                    <a href="{{ route('user.versions.index', $project) }}" class="w-full py-2 px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-semibold transition flex items-center justify-between">
                        <span>Version Releases</span>
                        <span class="font-mono text-[11px] text-emerald-500 font-bold">{{ $project->versions->count() }}</span>
                    </a>
                </div>
            </div>

            <!-- Recovery Notes / Dev Updates Changelog -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Recovery Updates Log</h3>
                    <button type="button" @click="openUpdateModal = true" class="text-emerald-500 text-xs hover:underline cursor-pointer font-mono">+ Log</button>
                </div>

                <div class="space-y-3">
                    @forelse($project->recoveryUpdates as $up)
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-3.5 text-xs space-y-1">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $up->update_title }}</div>
                            <p class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed">{{ $up->update_text }}</p>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono pt-1">{{ $up->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center py-3 font-mono">No dev updates posted yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Download Original Files -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Original Files Archive</h3>
                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($project->files as $file)
                        <div class="py-2.5 flex items-center justify-between text-xs gap-2">
                            <span class="text-slate-700 dark:text-slate-300 truncate max-w-[150px] font-mono text-[11px]">{{ $file->file_name }}</span>
                            <a href="{{ route('explore.files.download', [$project, $file]) }}" class="text-emerald-500 hover:underline font-mono text-[11px] shrink-0">Download</a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center py-2 font-mono">No files attached.</p>
                    @endforelse
                </div>
            </div>

            <!-- Voluntary Stewardship Relinquishment Card (Danger Zone) -->
            <div class="rounded-2xl border border-rose-200 dark:border-rose-950/60 bg-rose-50/40 dark:bg-rose-950/20 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-mono uppercase tracking-wider text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>Relinquish Stewardship</span>
                    </h3>
                </div>
                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Unable to continue maintaining this repository? You can voluntarily release custody back to the open preservation registry.
                </p>
                <button type="button" @click="openRelinquishModal = true" class="w-full py-2 px-3 rounded-lg border border-rose-300 dark:border-rose-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 text-xs font-bold transition cursor-pointer">
                    Relinquish Custody &rarr;
                </button>
            </div>

        </div>
    </div>

    <!-- Relinquish Modal -->
    <div x-show="openRelinquishModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-rose-800/60 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openRelinquishModal = false">
            <div class="flex items-center gap-3 text-rose-500">
                <div class="p-2 rounded-xl bg-rose-500/10">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Relinquish Stewardship</h3>
                    <p class="text-xs text-rose-400">Return repository to open adoption registry</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                By relinquishing custody, the project will return to <strong class="text-emerald-400">AVAILABLE</strong> status for the developer community. Your recorded tasks and history notes will remain preserved.
            </p>

            <form action="{{ route('user.recovery.relinquish', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="relinquish_reason" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Reason for Relinquishment *</label>
                    <textarea id="relinquish_reason" name="relinquish_reason" rows="3" required placeholder="Please explain why you are stepping down (e.g., lack of time, tech stack pivot)..." class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openRelinquishModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white transition cursor-pointer">Cancel</button>
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500 transition cursor-pointer">Confirm Relinquishment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div x-show="openTaskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openTaskModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Add Recovery Task</h3>
            <form action="{{ route('user.recovery.tasks.store', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="task_title" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Task Title *</label>
                    <input type="text" id="task_title" name="title" required placeholder="e.g. Fix JWT session expiration vulnerability" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="task_phase" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Phase *</label>
                    <select id="task_phase" name="phase" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="assessment">Phase 1: Assessment</option>
                        <option value="repair">Phase 2: Repair & Refactor</option>
                        <option value="development" selected>Phase 3: Development</option>
                        <option value="testing">Phase 4: Testing & Security</option>
                        <option value="deployment">Phase 5: Deployment & Release</option>
                    </select>
                </div>
                <div>
                    <label for="task_priority" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Priority *</label>
                    <select id="task_priority" name="priority" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label for="task_due_date" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Target Due Date</label>
                    <input type="date" id="task_due_date" name="due_date" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openTaskModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white transition cursor-pointer">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition cursor-pointer">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Post Note Modal -->
    <div x-show="openUpdateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openUpdateModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Post Recovery Dev Update</h3>
            <form action="{{ route('user.recovery.updates.store', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="update_title" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Update Title *</label>
                    <input type="text" id="update_title" name="update_title" required placeholder="e.g. Completed database migration and fixed foreign keys" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="update_text" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Update Notes / Details *</label>
                    <textarea id="update_text" name="update_text" rows="4" required placeholder="Detailed notes about technical work accomplished..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openUpdateModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white transition cursor-pointer">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition cursor-pointer">Post Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
