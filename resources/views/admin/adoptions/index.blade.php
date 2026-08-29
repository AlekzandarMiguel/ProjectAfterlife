@extends('layouts.admin', ['title' => 'Adoption Requests Queue — Project Afterlife', 'header' => 'Adoption Proposals Queue'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Adoption Requests & Proposals</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Review developer adoption roadmaps and authorize ownership transfers.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.adoption-requests.index', ['status' => 'pending']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-white' }}">
                Pending ({{ \App\Models\AdoptionRequest::where('status', 'pending')->count() }})
            </a>
            <a href="{{ route('admin.adoption-requests.index', ['status' => 'approved']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-white' }}">
                Approved
            </a>
        </div>
    </div>

    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $req)
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-semibold text-slate-900 dark:text-white">{{ $req->project->title }}</span>
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $req->status->badgeClasses() }}">
                                {{ $req->status->label() }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            Applicant: <strong class="text-slate-900 dark:text-white">{{ $req->applicant->name }}</strong> • Current Owner: <strong class="text-slate-700 dark:text-slate-300">{{ $req->project->owner->name }}</strong>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Target Date: {{ $req->expected_completion_date->format('M d, Y') }} • Submitted {{ $req->created_at->diffForHumans() }}</div>
                    </div>

                    <a href="{{ route('admin.adoption-requests.show', $req) }}" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition shrink-0">
                        Review Proposal & Transfer &rarr;
                    </a>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $requests->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/30 dark:bg-slate-900/30 p-12 text-center">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">No adoption requests in this queue</h3>
        </div>
    @endif
</div>
@endsection