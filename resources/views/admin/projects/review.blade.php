@extends('layouts.admin', ['title' => 'Review: ' . $project->title, 'header' => 'Project Verification'])

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-8" x-data="{ approveModal: false, rejectModal: false, revisionModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                <x-status-badge :status="$project->status" />
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $project->title }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="approveModal = true" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm">
                Approve & Publish
            </button>
            <button type="button" @click="revisionModal = true" class="rounded-lg bg-orange-600 px-3.5 py-2 text-xs font-semibold text-slate-900 dark:text-white hover:bg-orange-500 transition">
                Request Revision
            </button>
            <button type="button" @click="rejectModal = true" class="rounded-lg bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-rose-500 transition">
                Reject
            </button>
        </div>
    </div>

    <!-- Review Inspection Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold mb-3">Project Description</h3>
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
            </div>

            <!-- Reason for Abandonment -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-rose-400 font-semibold mb-2">Reason for Abandonment</h3>
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed italic">"{{ $project->reason_for_abandonment }}"</p>
            </div>

            <!-- Files -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold mb-4">Uploaded Archives & Files</h3>
                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($project->files as $file)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-medium text-slate-900 dark:text-white">{{ $file->file_name }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $file->file_type->label() }} • {{ $file->formatted_size }}</div>
                            </div>
                            <a href="{{ route('explore.files.download', [$project, $file]) }}" class="rounded bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs text-slate-800 dark:text-slate-200 hover:bg-slate-700">Download & Inspect</a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-slate-400">No files uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side: Ownership Declaration & Uploader Profile -->
        <div class="space-y-6">
            <div class="rounded-xl border border-emerald-950/60 bg-emerald-950/20 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold">Ownership Declaration</h3>
                @if($project->latestDeclaration)
                    <p class="text-xs text-emerald-200 italic">"{{ $project->latestDeclaration->declaration_text }}"</p>
                    <div class="text-[10px] text-emerald-400/80 font-mono">
                        Confirmed at: {{ $project->latestDeclaration->confirmed_at->format('M d, Y H:i:s') }}
                    </div>
                @else
                    <p class="text-xs text-rose-400">No declaration found.</p>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Uploader Identity</h3>
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800" src="{{ $project->owner->avatar_url }}" alt="{{ $project->owner->name }}">
                    <div>
                        <div class="text-xs font-semibold text-slate-900 dark:text-white">{{ $project->owner->name }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $project->owner->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div x-show="approveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="approveModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Approve Project</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">This will change the project status to <span class="text-emerald-400 font-mono font-bold">AVAILABLE</span> and make it available in the public repository for adoption.</p>
            <form action="{{ route('admin.projects.submissions.approve', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Approval Notes (Optional)</label>
                    <textarea name="admin_notes" rows="3" placeholder="Notes to be displayed to owner..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="approveModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">Confirm & Publish</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request Revision Modal -->
    <div x-show="revisionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="revisionModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Request Submission Revision</h3>
            <form action="{{ route('admin.projects.submissions.revision', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Revision Instructions *</label>
                    <textarea name="revision_instructions" rows="4" required placeholder="Explain what the uploader must correct before approval..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="revisionModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-orange-600 px-4 py-2 text-xs font-semibold text-slate-900 dark:text-white hover:bg-orange-500">Send Instructions</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="rejectModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Reject Submission</h3>
            <form action="{{ route('admin.projects.submissions.reject', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="4" required placeholder="Specify reason for rejection..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="rejectModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500">Reject Submission</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection