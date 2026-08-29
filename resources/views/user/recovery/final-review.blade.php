@extends('layouts.app', ['title' => 'Submit for Resurrection Review', 'header' => 'Final Review Submission'])

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="mb-8">
        <span class="text-xs font-mono text-purple-400 uppercase tracking-wider font-bold">Certification of Project Resurrection</span>
        <h1 class="text-2xl font-bold text-white tracking-tight mt-1">Submit {{ $project->title }} for Final Review</h1>
        <p class="text-xs text-slate-400 mt-1">
            Provide a complete recovery summary, completed feature list, and test verification results for administrator verification.
        </p>
    </div>

    <form action="{{ route('user.final-review.store', $project) }}" method="POST" class="rounded-2xl border border-purple-500/30 bg-slate-900/60 p-8 space-y-6 shadow-xl">
        @csrf

        <div>
            <label for="completion_summary" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Completion Summary *</label>
            <textarea id="completion_summary" name="completion_summary" rows="4" required placeholder="Summarize the overall recovery work accomplished from adoption to completion..." class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">{{ old('completion_summary') }}</textarea>
        </div>

        <div>
            <label for="completed_features" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Completed Features List *</label>
            <textarea id="completed_features" name="completed_features" rows="4" required placeholder="- Fixed authentication vulnerability&#10;- Implemented missing reporting module&#10;- Ported frontend to Vue 3" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 font-mono text-xs">{{ old('completed_features') }}</textarea>
        </div>

        <div>
            <label for="testing_summary" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Testing & Quality Assurance Summary *</label>
            <textarea id="testing_summary" name="testing_summary" rows="4" required placeholder="Describe unit test coverage, security audits, and browser/runtime compatibility verified..." class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">{{ old('testing_summary') }}</textarea>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-slate-800">
            <a href="{{ route('user.recovery.workspace', $project) }}" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-400 hover:text-white transition">Cancel</a>
            <button type="submit" class="rounded-lg bg-purple-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-purple-500 transition shadow-sm">
                Submit for Official Resurrection Review
            </button>
        </div>
    </form>
</div>
@endsection
