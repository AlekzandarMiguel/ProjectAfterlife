@extends('layouts.app', ['title' => $project->title . ' — Project Details', 'header' => 'Project Details'])

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                <x-status-badge :status="$project->status" />
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $project->title }}</h1>
        </div>

        <div class="flex items-center gap-3">
            @if($project->status->value === 'REVISION_REQUIRED' && $project->owner_id === auth()->id())
                <a href="{{ route('user.projects.edit', $project) }}" class="rounded-lg bg-orange-600 px-4 py-2 text-xs font-semibold text-slate-900 dark:text-white hover:bg-orange-500 transition">
                    Edit & Resubmit Revision
                </a>
            @endif

            @if($project->isUnderRecovery() && $project->owner_id === auth()->id())
                <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                    Open Recovery Workspace &rarr;
                </a>
            @endif

            <a href="{{ route('explore.show', $project) }}" target="_blank" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:text-white transition">
                Public View
            </a>
        </div>
    </div>

    <!-- Admin Revision Callout if applicable -->
    @if($project->status->value === 'REVISION_REQUIRED' && $project->revision_instructions)
        <div class="rounded-xl border border-orange-500/40 bg-orange-950/20 p-5">
            <h3 class="text-xs font-mono uppercase tracking-wider text-orange-400 font-semibold mb-1">Administrator Revision Instructions</h3>
            <p class="text-xs text-orange-200 leading-relaxed">{{ $project->revision_instructions }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold mb-3">Description & Scope</h3>
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
            </div>

            <!-- Reason for Abandonment -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-rose-400 font-semibold mb-2">Original Reason for Abandonment</h3>
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed italic">"{{ $project->reason_for_abandonment }}"</p>
            </div>

            <!-- Project Files -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold mb-4">Uploaded Files & Assets</h3>
                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($project->files as $file)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <span class="text-slate-900 dark:text-white font-medium">{{ $file->file_name }} ({{ $file->formatted_size }})</span>
                            <a href="{{ route('explore.files.download', [$project, $file]) }}" class="text-emerald-400 hover:underline font-mono text-[11px]">Download</a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-slate-400">No separate files attached.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side: Ownership, History -->
        <div class="space-y-6">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Ownership Details</h3>
                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block text-[10px]">Current Owner:</span>
                        <span class="text-slate-900 dark:text-white font-medium">{{ $project->owner->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block text-[10px]">Original Uploader:</span>
                        <span class="text-slate-900 dark:text-white font-medium">{{ $project->originalOwner->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold mb-4">Event Log</h3>
                <div class="relative pl-5 space-y-4 before:absolute before:left-2 before:top-1.5 before:bottom-1.5 before:w-0.5 before:bg-slate-100 dark:bg-slate-800 text-xs">
                    @foreach($project->history as $h)
                        <div class="relative">
                            <div class="absolute -left-5 top-1.5 h-2 w-2 rounded-full bg-emerald-400"></div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $h->action }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $h->description }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection