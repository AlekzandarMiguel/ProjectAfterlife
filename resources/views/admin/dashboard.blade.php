@extends('layouts.admin', ['title' => 'Admin Dashboard — Project Afterlife', 'header' => 'System Control Center'])

@section('content')
<div class="space-y-8">
    <!-- Telemetry Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-slate-400">Total Users</div>
            <div class="text-2xl font-bold text-white font-mono mt-1">{{ $stats['total_users'] }}</div>
            <a href="{{ route('admin.users.index') }}" class="text-[10px] text-purple-400 hover:underline mt-2 inline-block">Manage users &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-amber-400 font-semibold">Pending Submissions</div>
            <div class="text-2xl font-bold text-amber-400 font-mono mt-1">{{ $stats['pending_submissions'] }}</div>
            <a href="{{ route('admin.projects.submissions.index') }}" class="text-[10px] text-amber-400 hover:underline mt-2 inline-block">Review queue &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-amber-400 font-semibold">Pending Adoptions</div>
            <div class="text-2xl font-bold text-amber-400 font-mono mt-1">{{ $stats['pending_adoptions'] }}</div>
            <a href="{{ route('admin.adoption-requests.index') }}" class="text-[10px] text-amber-400 hover:underline mt-2 inline-block">Review proposals &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-purple-400 font-semibold">Final Reviews</div>
            <div class="text-2xl font-bold text-purple-400 font-mono mt-1">{{ $stats['pending_final_reviews'] }}</div>
            <a href="{{ route('admin.final-reviews.index') }}" class="text-[10px] text-purple-400 hover:underline mt-2 inline-block">Certify resurrection &rarr;</a>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-4">
            <div class="text-[11px] font-mono text-emerald-400 font-semibold">Resurrected Total</div>
            <div class="text-2xl font-bold text-emerald-400 font-mono mt-1">{{ $stats['resurrected_projects'] }}</div>
            <span class="text-[10px] text-slate-400 block mt-2">Active recoveries: {{ $stats['active_recoveries'] }}</span>
        </div>
    </div>

    <!-- Priority Action Queues Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Pending Submissions -->
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-amber-400 font-semibold">Pending Project Submissions</h3>
                <a href="{{ route('admin.projects.submissions.index') }}" class="text-[11px] text-slate-400 hover:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingSubmissions as $sub)
                    <div class="rounded-lg bg-slate-950 border border-slate-800 p-3 text-xs flex items-center justify-between gap-3">
                        <div class="truncate">
                            <div class="font-semibold text-white truncate">{{ $sub->title }}</div>
                            <div class="text-[10px] text-slate-400">By {{ $sub->owner->name }} • {{ $sub->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('admin.projects.submissions.show', $sub) }}" class="rounded bg-amber-600 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-amber-500 transition shrink-0">
                            Inspect
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No submissions pending review.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Adoption Proposals -->
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-amber-400 font-semibold">Pending Adoption Proposals</h3>
                <a href="{{ route('admin.adoption-requests.index') }}" class="text-[11px] text-slate-400 hover:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingAdoptions as $req)
                    <div class="rounded-lg bg-slate-950 border border-slate-800 p-3 text-xs flex items-center justify-between gap-3">
                        <div class="truncate">
                            <div class="font-semibold text-white truncate">{{ $req->project->title }}</div>
                            <div class="text-[10px] text-slate-400">Applicant: {{ $req->applicant->name }}</div>
                        </div>
                        <a href="{{ route('admin.adoption-requests.show', $req) }}" class="rounded bg-emerald-600 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-emerald-500 transition shrink-0">
                            Review
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No pending adoption requests.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Final Reviews -->
        <div class="rounded-xl border border-purple-500/30 bg-purple-950/10 p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-purple-400 font-semibold">Resurrection Final Reviews</h3>
                <a href="{{ route('admin.final-reviews.index') }}" class="text-[11px] text-slate-400 hover:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingFinalReviews as $fr)
                    <div class="rounded-lg bg-slate-950 border border-slate-800 p-3 text-xs flex items-center justify-between gap-3">
                        <div class="truncate">
                            <div class="font-semibold text-white truncate">{{ $fr->project->title }}</div>
                            <div class="text-[10px] text-purple-300 font-mono">Owner: {{ $fr->project->owner->name }}</div>
                        </div>
                        <a href="{{ route('admin.final-reviews.show', $fr) }}" class="rounded bg-purple-600 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-purple-500 transition shrink-0">
                            Certify
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No projects awaiting final resurrection review.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Ownership Transfers Ledger -->
    <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white tracking-tight uppercase font-mono">Recent Ownership Transfers</h3>
            <a href="{{ route('admin.ownership-transfers.index') }}" class="text-xs text-purple-400 hover:underline">View Full Ledger</a>
        </div>

        <div class="divide-y divide-slate-800 border border-slate-800 bg-slate-950 rounded-xl overflow-hidden text-xs">
            @forelse($recentTransfers as $t)
                <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="font-semibold text-white">{{ $t->project->title }}</span>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            From <span class="text-slate-300">{{ $t->previousOwner->name }}</span> &rarr; To <span class="text-emerald-400 font-medium">{{ $t->newOwner->name }}</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-slate-400 font-mono">
                        Approved by {{ $t->adminApprover->name }} on {{ $t->transferred_at->format('M d, Y H:i') }}
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-slate-400 text-xs">No ownership transfers recorded yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
