@extends('layouts.admin', ['title' => 'Admin Dashboard — Project Afterlife', 'header' => 'System Control Center'])

@section('content')
<div class="space-y-8">
    <!-- Telemetry Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-5 shadow-xs theme-interactive-card cursor-default">
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 font-medium">Total Users</div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $stats['total_users'] }}</div>
            <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-purple-600 dark:text-purple-400 hover:underline mt-2 inline-block">Manage users &rarr;</a>
        </div>
        <div class="rounded-2xl border border-amber-200 dark:border-slate-800 bg-amber-50/50 dark:bg-slate-900/40 p-5 shadow-xs theme-interactive-card cursor-default">
            <div class="text-[11px] font-mono text-amber-700 dark:text-amber-400 font-bold">Pending Submissions</div>
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono mt-1">{{ $stats['pending_submissions'] }}</div>
            <a href="{{ route('admin.projects.submissions.index') }}" class="text-[10px] font-bold text-amber-700 dark:text-amber-400 hover:underline mt-2 inline-block">Review queue &rarr;</a>
        </div>
        <div class="rounded-2xl border border-amber-200 dark:border-slate-800 bg-amber-50/50 dark:bg-slate-900/40 p-5 shadow-xs theme-interactive-card cursor-default">
            <div class="text-[11px] font-mono text-amber-700 dark:text-amber-400 font-bold">Pending Adoptions</div>
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono mt-1">{{ $stats['pending_adoptions'] }}</div>
            <a href="{{ route('admin.adoption-requests.index') }}" class="text-[10px] font-bold text-amber-700 dark:text-amber-400 hover:underline mt-2 inline-block">Review proposals &rarr;</a>
        </div>
        <div class="rounded-2xl border border-purple-200 dark:border-slate-800 bg-purple-50/50 dark:bg-slate-900/40 p-5 shadow-xs theme-interactive-card cursor-default">
            <div class="text-[11px] font-mono text-purple-700 dark:text-purple-400 font-bold">Final Reviews</div>
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 font-mono mt-1">{{ $stats['pending_final_reviews'] }}</div>
            <a href="{{ route('admin.final-reviews.index') }}" class="text-[10px] font-bold text-purple-700 dark:text-purple-400 hover:underline mt-2 inline-block">Certify resurrection &rarr;</a>
        </div>
        <div class="rounded-2xl border border-emerald-200 dark:border-slate-800 bg-emerald-50/50 dark:bg-slate-900/40 p-5 shadow-xs theme-interactive-card cursor-default">
            <div class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-bold">Resurrected Total</div>
            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono mt-1">{{ $stats['resurrected_projects'] }}</div>
            <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-2">Active recoveries: {{ $stats['active_recoveries'] }}</span>
        </div>
    </div>

    <!-- Priority Action Queues Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Pending Submissions -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-amber-600 dark:text-amber-400 font-bold">Pending Submissions</h3>
                <a href="{{ route('admin.projects.submissions.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-900 dark:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingSubmissions as $sub)
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-3.5 text-xs flex items-center justify-between gap-3 shadow-xs">
                        <div class="truncate">
                            <div class="font-bold text-slate-900 dark:text-white truncate">{{ $sub->title }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">By {{ $sub->owner->name }} &bull; {{ $sub->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('admin.projects.submissions.show', $sub) }}" class="rounded-lg bg-amber-600 px-3 py-1.5 text-[11px] font-bold text-white hover:bg-amber-500 transition shrink-0 shadow-xs">
                            Inspect
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 text-center py-6">No submissions pending review.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Adoption Proposals -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold">Pending Adoptions</h3>
                <a href="{{ route('admin.adoption-requests.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-900 dark:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingAdoptions as $req)
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-3.5 text-xs flex items-center justify-between gap-3 shadow-xs">
                        <div class="truncate">
                            <div class="font-bold text-slate-900 dark:text-white truncate">{{ $req->project->title }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">Applicant: {{ $req->applicant->name }}</div>
                        </div>
                        <a href="{{ route('admin.adoption-requests.show', $req) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-[11px] font-bold text-white hover:bg-emerald-500 transition shrink-0 shadow-xs">
                            Review
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 text-center py-6">No pending adoption requests.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Final Reviews -->
        <div class="rounded-2xl border border-purple-200 dark:border-purple-500/30 bg-purple-50/40 dark:bg-purple-950/10 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-mono uppercase tracking-wider text-purple-700 dark:text-purple-400 font-bold">Resurrection Reviews</h3>
                <a href="{{ route('admin.final-reviews.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-900 dark:text-white">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingFinalReviews as $fr)
                    <div class="rounded-xl bg-white dark:bg-slate-950 border border-purple-200 dark:border-slate-800 p-3.5 text-xs flex items-center justify-between gap-3 shadow-xs">
                        <div class="truncate">
                            <div class="font-bold text-slate-900 dark:text-white truncate">{{ $fr->project->title }}</div>
                            <div class="text-[10px] text-purple-700 dark:text-purple-300 font-mono mt-0.5">Owner: {{ $fr->project->owner->name }}</div>
                        </div>
                        <a href="{{ route('admin.final-reviews.show', $fr) }}" class="rounded-lg bg-purple-600 px-3 py-1.5 text-[11px] font-bold text-white hover:bg-purple-500 transition shrink-0 shadow-xs">
                            Certify
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 text-center py-6">No projects awaiting final resurrection review.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Ownership Transfers Ledger -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-mono uppercase tracking-wider text-slate-600 dark:text-slate-300 font-bold">Recent Ownership Transfers</h3>
            <a href="{{ route('admin.ownership-transfers.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">View Full Ledger &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($recentTransfers as $transfer)
                <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div>
                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $transfer->project?->title ?? 'Software Project' }}</span>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            From <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $transfer->previousOwner?->name ?? 'Original Creator' }}</span> &rarr; To <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $transfer->newOwner?->name ?? 'New Maintainer' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">Approved by {{ $transfer->adminApprover?->name ?? $transfer->admin?->name ?? 'System Administrator' }} on {{ $transfer->transferred_at ? $transfer->transferred_at->format('M d, Y H:i') : ($transfer->created_at ? $transfer->created_at->format('M d, Y H:i') : 'Recently') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 text-center py-6">No ownership transfers recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection