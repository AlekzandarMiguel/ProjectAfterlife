@extends('layouts.app', ['title' => 'Recovery: ' . $project->title, 'header' => 'Recovery Workspace'])

@section('content')
<div class="space-y-8" x-data="{ openTaskModal: false, openUpdateModal: false, openVersionModal: false }">
    <!-- Top Workspace Header -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-mono text-slate-400">{{ $project->category->name ?? 'Project' }}</span>
                    <x-status-badge :status="$project->status" />
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ $project->title }}</h1>
                <p class="text-xs text-slate-400 mt-1 max-w-2xl">{{ $project->short_description }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" @click="openTaskModal = true" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition flex items-center gap-1.5 shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Add Task</span>
                </button>
                <button type="button" @click="openUpdateModal = true" class="rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition">
                    Post Note
                </button>
                <a href="{{ route('user.versions.index', $project) }}" class="rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition">
                    Versions ({{ $project->versions->count() }})
                </a>
                <a href="{{ route('user.final-review.create', $project) }}" class="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition shadow-sm">
                    Submit for Resurrection &rarr;
                </a>
            </div>
        </div>

        <!-- Strict Dynamic Progress Engine Bar -->
        <div class="mt-6 pt-6 border-t border-slate-800/80">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-mono">
                <span class="font-semibold text-slate-200 uppercase tracking-wider text-[10px]">Calculated Recovery Progress:</span>
                <span class="font-bold text-emerald-400 text-sm">{{ $progress }}%</span>
            </div>
            <div class="h-2.5 w-full rounded-full bg-slate-800 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mt-2">
                <span>{{ $completedTasks }} of {{ $totalTasks }} checklist tasks completed</span>
                <span>Auto-computed from actual task state</span>
            </div>
        </div>
    </div>

    <!-- Phase-Based Kanban & Task Board -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Tasks Board (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono">Recovery Checklist & Milestones</h3>
                <span class="text-xs text-slate-400 font-mono">{{ $tasks->count() }} total tasks</span>
            </div>

            @if($tasks->count() > 0)
                <!-- Group tasks by Phase -->
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
                            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4 space-y-3">
                                <div class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold flex items-center justify-between">
                                    <span>{{ $phaseTitle }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $phaseTasks->where('status.value', 'completed')->count() }}/{{ $phaseTasks->count() }}</span>
                                </div>

                                <div class="space-y-2">
                                    @foreach($phaseTasks as $task)
                                        <div class="rounded-lg border border-slate-800/80 bg-slate-950/80 p-3 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                                            <div class="flex items-start gap-3">
                                                <!-- Quick Toggle Status Form -->
                                                <form action="{{ route('user.recovery.tasks.update', [$project, $task]) }}" method="POST" class="mt-0.5">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($task->status->value === 'completed')
                                                        <input type="hidden" name="status" value="todo">
                                                        <button type="submit" class="h-4 w-4 rounded bg-emerald-500 text-slate-950 flex items-center justify-center cursor-pointer">
                                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="h-4 w-4 rounded border border-slate-700 bg-slate-900 hover:border-emerald-500 cursor-pointer"></button>
                                                    @endif
                                                </form>

                                                <div>
                                                    <div class="text-xs font-medium {{ $task->status->value === 'completed' ? 'text-slate-400 line-through' : 'text-white' }}">
                                                        {{ $task->title }}
                                                    </div>
                                                    @if($task->description)
                                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $task->description }}</div>
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
                <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-8 text-center">
                    <p class="text-xs text-slate-400">No recovery tasks added yet. Create tasks to calculate your recovery progress.</p>
                    <button type="button" @click="openTaskModal = true" class="mt-3 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                        Add First Recovery Task
                    </button>
                </div>
            @endif
        </div>

        <!-- Right Side: Updates Log & Original Project Files -->
        <div class="space-y-6">
            <!-- Recovery Notes / Dev Updates -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold">Recovery Updates & Notes</h3>
                    <button type="button" @click="openUpdateModal = true" class="text-emerald-400 text-xs hover:underline">+ Post</button>
                </div>

                <div class="space-y-3">
                    @forelse($project->recoveryUpdates as $up)
                        <div class="rounded-lg bg-slate-950 border border-slate-800 p-3 text-xs">
                            <div class="font-semibold text-white">{{ $up->update_title }}</div>
                            <p class="text-slate-400 text-[11px] mt-1 leading-relaxed">{{ $up->update_text }}</p>
                            <div class="text-[10px] text-slate-400 font-mono mt-2">{{ $up->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-2">No recovery updates posted yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Download Original Files -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold">Original Files Archive</h3>
                <div class="divide-y divide-slate-800">
                    @foreach($project->files as $file)
                        <div class="py-2 flex items-center justify-between text-xs">
                            <span class="text-slate-300 truncate max-w-[140px]">{{ $file->file_name }}</span>
                            <a href="{{ route('explore.files.download', [$project, $file]) }}" class="text-emerald-400 hover:underline font-mono text-[11px]">Download</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div x-show="openTaskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openTaskModal = false">
            <h3 class="text-base font-bold text-white">Add Recovery Task</h3>
            <form action="{{ route('user.recovery.tasks.store', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="task_title" class="block text-xs font-medium text-slate-300">Task Title *</label>
                    <input type="text" id="task_title" name="title" required placeholder="e.g. Fix JWT session expiration vulnerability" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="task_phase" class="block text-xs font-medium text-slate-300">Phase *</label>
                    <select id="task_phase" name="phase" required class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="assessment">Phase 1: Assessment</option>
                        <option value="repair">Phase 2: Repair & Refactor</option>
                        <option value="development" selected>Phase 3: Development</option>
                        <option value="testing">Phase 4: Testing & Security</option>
                        <option value="deployment">Phase 5: Deployment & Release</option>
                    </select>
                </div>
                <div>
                    <label for="task_priority" class="block text-xs font-medium text-slate-300">Priority *</label>
                    <select id="task_priority" name="priority" required class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label for="task_due_date" class="block text-xs font-medium text-slate-300">Target Due Date</label>
                    <input type="date" id="task_due_date" name="due_date" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openTaskModal = false" class="rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-slate-400 hover:text-white transition">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Post Note Modal -->
    <div x-show="openUpdateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openUpdateModal = false">
            <h3 class="text-base font-bold text-white">Post Recovery Dev Update</h3>
            <form action="{{ route('user.recovery.updates.store', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="update_title" class="block text-xs font-medium text-slate-300">Update Title *</label>
                    <input type="text" id="update_title" name="update_title" required placeholder="e.g. Completed database migration and fixed foreign keys" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="update_text" class="block text-xs font-medium text-slate-300">Update Notes / Details *</label>
                    <textarea id="update_text" name="update_text" rows="4" required placeholder="Detailed notes about technical work accomplished..." class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openUpdateModal = false" class="rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-slate-400 hover:text-white transition">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">Post Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
