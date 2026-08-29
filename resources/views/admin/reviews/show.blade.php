@extends('layouts.admin', ['title' => 'Resurrection Certification: ' . $finalReview->project->title, 'header' => 'Resurrection Certification'])

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-8" x-data="{ approveModal: false, revisionModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-mono text-purple-400">Resurrection Final Submission</span>
                <span class="rounded px-2 py-0.5 text-xs font-mono font-bold bg-purple-500/20 text-purple-300">
                    {{ $finalReview->status->label() }}
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $finalReview->project->title }}</h1>
        </div>

        @if($finalReview->status->value === 'pending')
            <div class="flex items-center gap-2">
                <button type="button" @click="approveModal = true" class="rounded-lg bg-purple-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-purple-500 transition shadow-lg flex items-center gap-1.5">
                    <span>🏆 Certify & Grant RESURRECTED Status</span>
                </button>
                <button type="button" @click="revisionModal = true" class="rounded-lg bg-orange-600 px-3.5 py-2.5 text-xs font-semibold text-slate-900 dark:text-white hover:bg-orange-500 transition">
                    Request Fixes
                </button>
            </div>
        @endif
    </div>

    <!-- Details Grid -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-6 text-xs text-slate-700 dark:text-slate-300">
        <div>
            <h3 class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-2">Completion Summary</h3>
            <p class="text-slate-900 dark:text-white leading-relaxed text-sm">{{ $finalReview->completion_summary }}</p>
        </div>

        <div>
            <h3 class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-2">Completed Features</h3>
            <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-emerald-300 whitespace-pre-line leading-relaxed">
                {{ $finalReview->completed_features }}
            </div>
        </div>

        <div>
            <h3 class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-2">Testing & Verification Results</h3>
            <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                {{ $finalReview->testing_summary }}
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div x-show="approveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="approveModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Approve Resurrection</h3>
            <p class="text-xs text-slate-700 dark:text-slate-300">This officially certifies the project as <strong class="text-purple-400">RESURRECTED</strong> and places it in the public Hall of Fame.</p>
            <form action="{{ route('admin.final-reviews.approve', $finalReview) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Administrator Certification Feedback</label>
                    <textarea name="admin_feedback" rows="3" placeholder="Congratulations to the developer..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="approveModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500">Confirm Resurrection</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revision Modal -->
    <div x-show="revisionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="revisionModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Request Further Fixes</h3>
            <form action="{{ route('admin.final-reviews.revision', $finalReview) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Feedback *</label>
                    <textarea name="admin_feedback" rows="3" required placeholder="Specific features or tests that must be completed..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="revisionModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-orange-600 px-4 py-2 text-xs font-semibold text-slate-900 dark:text-white hover:bg-orange-500">Return to Owner</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection