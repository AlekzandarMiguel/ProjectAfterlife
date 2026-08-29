@extends('layouts.app', ['title' => 'Apply to Adopt ' . $project->title, 'header' => 'Adoption Proposal'])

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="mb-8">
        <span class="text-xs font-mono text-emerald-400 uppercase tracking-wider font-bold">Formal Adoption Request</span>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-1">Adopt: {{ $project->title }}</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Submit your recovery plan and timeline. The administrator will review your proposal to authorize ownership transfer.</p>
    </div>

    <form action="{{ route('user.adoptions.store', $project) }}" method="POST" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-8 space-y-6 shadow-xl">
        @csrf

        <div>
            <label for="reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">Why do you want to adopt this software? *</label>
            <textarea id="reason" name="reason" rows="3" required placeholder="Explain your motivation for continuing this specific project..." class="mt-1.5 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('reason') }}</textarea>
        </div>

        <div>
            <label for="proposed_improvements" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">Proposed Improvements & Bug Fixes *</label>
            <textarea id="proposed_improvements" name="proposed_improvements" rows="3" required placeholder="Specific technical modules you intend to refactor, repair, or add..." class="mt-1.5 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('proposed_improvements') }}</textarea>
        </div>

        <div>
            <label for="recovery_plan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">Structured Recovery Plan (Phases & Milestones) *</label>
            <textarea id="recovery_plan" name="recovery_plan" rows="5" required placeholder="Phase 1: Code review & security audit&#10;Phase 2: Database migration & bug fixes&#10;Phase 3: New features & tests&#10;Phase 4: Final deployment release" class="mt-1.5 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono text-xs">{{ old('recovery_plan') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="expected_completion_date" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">Target Completion Date *</label>
                <input type="date" id="expected_completion_date" name="expected_completion_date" required value="{{ old('expected_completion_date') }}" class="mt-1.5 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label for="relevant_skills" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">Relevant Skills & Experience</label>
                <input type="text" id="relevant_skills" name="relevant_skills" value="{{ old('relevant_skills') }}" placeholder="e.g. 5 yrs PHP, Go, Docker, WebRTC" class="mt-1.5 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('explore.show', $project) }}" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2.5 text-xs text-slate-500 dark:text-slate-400 hover:text-white transition">Cancel</a>
            <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm">
                Submit Adoption Proposal
            </button>
        </div>
    </form>
</div>
@endsection