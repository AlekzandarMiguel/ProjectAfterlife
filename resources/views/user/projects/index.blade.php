@extends('layouts.app', ['title' => 'My Projects — Project Afterlife', 'header' => 'My Projects'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
        <!-- Tabs: Uploaded vs Adopted -->
        <div class="flex items-center gap-2">
            <a href="{{ route('user.projects.index', ['tab' => 'uploaded']) }}" class="rounded-lg px-4 py-2 text-xs font-semibold transition {{ $tab === 'uploaded' ? 'bg-emerald-600 text-white' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Uploaded by Me
            </a>
            <a href="{{ route('user.projects.index', ['tab' => 'adopted']) }}" class="rounded-lg px-4 py-2 text-xs font-semibold transition {{ $tab === 'adopted' ? 'bg-emerald-600 text-white' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Adopted Projects
            </a>
        </div>

        <a href="{{ route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition self-start sm:self-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Upload New Project</span>
        </a>
    </div>

    @if($projects->count() > 0)
        <div class="space-y-4">
            @foreach($projects as $project)
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-slate-700 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-mono text-slate-400">{{ $project->category->name ?? 'Project' }}</span>
                            <x-status-badge :status="$project->status" />
                        </div>
                        <a href="{{ route('user.projects.show', $project) }}" class="text-base font-semibold text-white hover:text-emerald-400 transition block">
                            {{ $project->title }}
                        </a>
                        <p class="text-xs text-slate-400 line-clamp-1 max-w-2xl">{{ $project->short_description }}</p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @if($project->status->value === 'REVISION_REQUIRED')
                            <a href="{{ route('user.projects.edit', $project) }}" class="rounded-lg bg-orange-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-orange-500 transition">
                                Edit & Resubmit
                            </a>
                        @endif

                        @if($project->isUnderRecovery() && $project->owner_id === auth()->id())
                            <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                                Recovery Workspace
                            </a>
                        @endif

                        <a href="{{ route('user.projects.show', $project) }}" class="rounded-lg border border-slate-800 bg-slate-950 px-3 py-1.5 text-xs text-slate-300 hover:text-white transition">
                            Details &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $projects->links() }}
        </div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
            <h3 class="text-base font-semibold text-white">No projects listed</h3>
            <p class="text-xs text-slate-400 mt-1">You have not {{ $tab === 'adopted' ? 'adopted any projects yet' : 'uploaded any abandoned projects yet' }}.</p>
            <div class="mt-4">
                <a href="{{ $tab === 'adopted' ? route('explore.index') : route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                    {{ $tab === 'adopted' ? 'Browse Available Projects' : 'Upload Your First Project' }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
