@extends('layouts.admin', ['title' => 'Resurrection Final Reviews — Project Afterlife', 'header' => 'Resurrection Certification Queue'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Final Resurrection Submissions</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Certify fully recovered software projects for permanent RESURRECTED status.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.final-reviews.index', ['status' => 'pending']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'pending' ? 'bg-purple-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-white' }}">
                Pending Review
            </a>
            <a href="{{ route('admin.final-reviews.index', ['status' => 'approved']) }}" class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-white' }}">
                Certified Resurrected
            </a>
        </div>
    </div>

    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $fr)
                <div class="rounded-xl border border-purple-500/30 bg-white/40 dark:bg-slate-900/40 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-semibold text-slate-900 dark:text-white">{{ $fr->project->title }}</span>
                            <span class="rounded px-2 py-0.5 text-xs font-mono font-bold bg-purple-500/20 text-purple-300">
                                {{ $fr->status->label() }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            Submitted by <span class="text-slate-900 dark:text-white">{{ $fr->submitter->name }}</span> • {{ $fr->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <a href="{{ route('admin.final-reviews.show', $fr) }}" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition shrink-0">
                        Inspect & Certify &rarr;
                    </a>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $reviews->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/30 dark:bg-slate-900/30 p-12 text-center">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">No final review submissions in this queue</h3>
        </div>
    @endif
</div>
@endsection