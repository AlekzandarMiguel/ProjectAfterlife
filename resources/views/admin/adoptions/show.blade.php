@extends('layouts.admin', ['title' => 'Review Adoption Proposal', 'header' => 'Adoption Review & Ownership Transfer'])

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-8" x-data="{ approveModal: false, rejectModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">Adoption Proposal #{{ $adoptionRequest->id }}</span>
                <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $adoptionRequest->status->badgeClasses() }}">
                    {{ $adoptionRequest->status->label() }}
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $adoptionRequest->project->title }}</h1>
        </div>

        @if($adoptionRequest->status->value === 'pending')
            <div class="flex items-center gap-2">
                <button type="button" @click="approveModal = true" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    <span>Approve & Execute Ownership Transfer</span>
                </button>
                <button type="button" @click="rejectModal = true" class="rounded-lg bg-rose-600 px-3.5 py-2.5 text-xs font-semibold text-white hover:bg-rose-500 transition">
                    Reject
                </button>
            </div>
        @endif
    </div>

    <!-- Proposal Details & Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-4">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Adoption Motivation & Plan</h3>
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Reason for Adoption:</div>
                    <p class="text-xs text-slate-900 dark:text-white leading-relaxed">{{ $adoptionRequest->reason }}</p>
                </div>
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Proposed Technical Improvements:</div>
                    <p class="text-xs text-slate-900 dark:text-white leading-relaxed">{{ $adoptionRequest->proposed_improvements }}</p>
                </div>
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Recovery Roadmap:</div>
                    <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs text-emerald-300 whitespace-pre-line leading-relaxed">
                        {{ $adoptionRequest->recovery_plan }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Transfer Parties Comparison -->
        <div class="space-y-6">
            <!-- New Owner / Applicant -->
            <div class="rounded-xl border border-emerald-950/60 bg-emerald-950/20 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold">Applicant (Prospective Owner)</h3>
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 " src="{{ $adoptionRequest->applicant->avatar_url }}" alt="{{ $adoptionRequest->applicant->name }}">
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">{{ $adoptionRequest->applicant->name }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $adoptionRequest->applicant->email }}</div>
                    </div>
                </div>
                @if($adoptionRequest->relevant_skills)
                    <div class="text-[11px] text-slate-700 dark:text-slate-300 pt-2 border-t border-emerald-900/60">
                        <span class="font-semibold text-emerald-300">Stated Skills:</span> {{ $adoptionRequest->relevant_skills }}
                    </div>
                @endif
            </div>

            <!-- Current Owner -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Current Project Owner</h3>
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800" src="{{ $adoptionRequest->project->owner->avatar_url }}" alt="{{ $adoptionRequest->project->owner->name }}">
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white">{{ $adoptionRequest->project->owner->name }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $adoptionRequest->project->owner->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Transfer Modal -->
    <div x-show="approveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="approveModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Execute Ownership Transfer</h3>
            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                This triggers an <strong class="text-emerald-400">Atomic Database Transaction</strong>:
            </p>
            <ul class="list-disc pl-5 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
                <li>Swaps project owner to <strong class="text-slate-900 dark:text-white">{{ $adoptionRequest->applicant->name }}</strong>.</li>
                <li>Changes project status to <strong class="text-sky-400">UNDER_RECOVERY</strong>.</li>
                <li>Preserves <strong class="text-slate-900 dark:text-white">{{ $adoptionRequest->project->originalOwner->name }}</strong> as original uploader in permanent history.</li>
                <li>Logs immutable transfer in ownership ledger.</li>
            </ul>

            <form action="{{ route('admin.adoption-requests.approve', $adoptionRequest) }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Admin Approval Notes (Optional)</label>
                    <textarea name="admin_notes" rows="2" placeholder="Notes to applicant and previous owner..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="approveModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">Confirm Ownership Transfer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="rejectModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Reject Adoption Proposal</h3>
            <form action="{{ route('admin.adoption-requests.reject', $adoptionRequest) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="3" required placeholder="Explain why the proposal was declined..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="rejectModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500">Reject Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection