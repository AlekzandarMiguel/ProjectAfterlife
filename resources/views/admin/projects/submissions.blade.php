@extends('layouts.admin', ['title' => 'Project Submissions Review — Project Afterlife', 'header' => 'Project Submissions Queue'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-white tracking-tight">Project Submissions Verification</h2>
            <p class="text-xs text-slate-400 mt-0.5">Inspect incoming abandoned projects for source code validity, licensing, and declaration confirmation.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.projects.submissions.index', ['status' => 'PENDING_REVIEW']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'PENDING_REVIEW' ? 'bg-amber-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
                Pending ({{ \App\Models\Project::where('status', 'PENDING_REVIEW')->count() }})
            </a>
            <a href="{{ route('admin.projects.submissions.index', ['status' => 'REVISION_REQUIRED']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'REVISION_REQUIRED' ? 'bg-orange-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
                Revision Required
            </a>
        </div>
    </div>

    @if($submissions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($submissions as $sub)
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-mono text-slate-400">{{ $sub->category->name ?? 'General' }}</span>
                            <x-status-badge :status="$sub->status" />
                        </div>
                        <h3 class="text-base font-semibold text-white">{{ $sub->title }}</h3>
                        <p class="mt-2 text-xs text-slate-400 line-clamp-3 leading-relaxed">{{ $sub->short_description }}</p>

                        <div class="mt-4 p-3 rounded-lg bg-slate-950 border border-slate-800 text-[11px] space-y-1">
                            <div><span class="text-slate-400">Uploader:</span> <span class="text-white font-medium">{{ $sub->owner->name }}</span></div>
                            <div><span class="text-slate-400">Dev Status:</span> <span class="text-slate-300 font-mono">{{ $sub->development_status->label() }}</span></div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono">{{ $sub->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.projects.submissions.show', $sub) }}" class="rounded-lg bg-amber-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-amber-500 transition">
                            Inspect Submission &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $submissions->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
            <h3 class="text-base font-semibold text-white">No submissions in this queue</h3>
            <p class="text-xs text-slate-400 mt-1">All incoming projects have been processed.</p>
        </div>
    @endif
</div>
@endsection
