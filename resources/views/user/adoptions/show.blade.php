@extends('layouts.app', ['title' => 'Adoption Request Details', 'header' => 'Adoption Request Details'])

@section('content')
<div class="max-w-3xl mx-auto py-6 space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <span class="text-xs font-mono text-slate-500 dark:text-slate-400">Adoption Application #{{ $adoptionRequest->id }}</span>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">{{ $adoptionRequest->project->title }}</h1>
        </div>
        <span class="inline-flex items-center rounded px-2.5 py-1 text-xs font-medium {{ $adoptionRequest->status->badgeClasses() }}">
            {{ $adoptionRequest->status->label() }}
        </span>
    </div>

    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-6 text-xs text-slate-700 dark:text-slate-300">
        <div>
            <div class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-1">Reason for Adoption</div>
            <p class="text-slate-900 dark:text-white leading-relaxed">{{ $adoptionRequest->reason }}</p>
        </div>

        <div>
            <div class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-1">Proposed Improvements</div>
            <p class="text-slate-900 dark:text-white leading-relaxed">{{ $adoptionRequest->proposed_improvements }}</p>
        </div>

        <div>
            <div class="font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold mb-1">Recovery Plan</div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3.5 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs whitespace-pre-line text-emerald-300">
                {{ $adoptionRequest->recovery_plan }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-[11px]">
            <div>
                <span class="text-slate-500 dark:text-slate-400 block font-mono">Target Completion:</span>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $adoptionRequest->expected_completion_date->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 block font-mono">Applicant:</span>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $adoptionRequest->applicant->name }}</span>
            </div>
        </div>
    </div>
</div>
@endsection