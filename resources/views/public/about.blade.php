@extends('layouts.guest', ['title' => 'About Project Afterlife'])

@section('content')
<div class="py-16 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <div class="border-b border-slate-800 pb-8 mb-12">
        <h1 class="text-3xl font-bold text-white sm:text-4xl">About Project Afterlife</h1>
        <p class="mt-4 text-base text-slate-400 leading-relaxed">
            The Web-Based Abandoned Software Project Recovery and Ownership Transfer System is a specialized developer platform engineered to combat code obsolescence and preserve valuable software.
        </p>
    </div>

    <div class="space-y-12 text-slate-300 text-sm leading-relaxed">
        <section>
            <h2 class="text-xl font-semibold text-white mb-3">The Problem: Software Wastage</h2>
            <p>
                Every year, thousands of capable applications, developer tools, libraries, and prototypes are abandoned due to lack of maintainer time, changes in career, or loss of funding. In standard repositories, these projects silently rot without any structured mechanism to legally or technically transfer ownership to motivated new developers.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-white mb-3">Our Core Principles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="font-semibold text-white text-base mb-1">1. Zero AI Hallucinations</h3>
                    <p class="text-xs text-slate-400">All code validation, adoption proposals, progress calculations, and resurrection reviews are conducted by real human engineers and platform administrators.</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="font-semibold text-white text-base mb-1">2. Immutable Ownership History</h3>
                    <p class="text-xs text-slate-400">Ownership transfers never erase the original author. Every project preserves a complete, tamper-evident timeline of who originally created the software and who recovered it.</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="font-semibold text-white text-base mb-1">3. Dynamic Progress Engine</h3>
                    <p class="text-xs text-slate-400">Recovery progress cannot be arbitrarily typed. It is strictly computed using the formula: <span class="font-mono text-emerald-400">(completed_tasks / total_tasks) * 100</span>.</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-5">
                    <h3 class="font-semibold text-white text-base mb-1">4. Secure Non-Executable Storage</h3>
                    <p class="text-xs text-slate-400">Uploaded source archives and SQL files are stored in protected directory paths with tokenized download streams and never executed on the host server.</p>
                </div>
            </div>
        </section>

        <section class="border-t border-slate-800 pt-8">
            <h2 class="text-xl font-semibold text-white mb-3">System Roles</h2>
            <p>
                Project Afterlife operates under a strict two-role architecture:
            </p>
            <ul class="list-disc pl-5 space-y-2 mt-3 text-xs text-slate-400">
                <li><strong class="text-white">ADMIN:</strong> Controls platform moderation, inspects uploaded source archives, reviews adoption proposals, executes atomic database transfers, monitors recovery activity, and certifies resurrected projects.</li>
                <li><strong class="text-white">USER:</strong> Normal developers who can upload abandoned projects, browse the repository, apply for adoption, manage their active recovery workspaces, and release new versions.</li>
            </ul>
        </section>
    </div>
</div>
@endsection
