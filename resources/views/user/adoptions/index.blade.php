@extends('layouts.app', ['title' => 'My Adoption Requests — Project Afterlife', 'header' => 'Adoption Requests'])

@section('content')
<div class="space-y-6">
    <div class="pb-4 border-b border-slate-800">
        <h2 class="text-lg font-bold text-white tracking-tight">My Adoption Requests</h2>
        <p class="text-xs text-slate-400 mt-0.5">Track the status of your submitted adoption proposals.</p>
    </div>

    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $req)
                <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-semibold text-white">{{ $req->project->title }}</span>
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $req->status->badgeClasses() }}">
                                {{ $req->status->label() }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-400">Target completion: {{ $req->expected_completion_date->format('M d, Y') }} • Submitted {{ $req->created_at->diffForHumans() }}</div>
                        @if($req->admin_notes)
                            <div class="mt-2 text-xs text-slate-300 bg-slate-950/60 p-2.5 rounded-lg border border-slate-800">
                                <span class="font-semibold text-slate-200">Admin Notes:</span> {{ $req->admin_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @if($req->status->value === 'approved' && $req->project->owner_id === auth()->id())
                            <a href="{{ route('user.recovery.workspace', $req->project) }}" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                                Open Recovery Workspace
                            </a>
                        @endif
                        <a href="{{ route('user.adoptions.show', $req) }}" class="rounded-lg border border-slate-800 bg-slate-950 px-3.5 py-2 text-xs text-slate-300 hover:text-white transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $requests->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
            <p class="text-xs text-slate-400">You have not submitted any adoption requests yet.</p>
            <a href="{{ route('explore.index') }}" class="inline-block mt-3 text-xs font-semibold text-emerald-400 hover:underline">
                Explore Available Projects &rarr;
            </a>
        </div>
    @endif
</div>
@endsection
