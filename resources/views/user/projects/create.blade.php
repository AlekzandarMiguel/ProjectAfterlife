@extends('layouts.app', ['title' => 'Upload Abandoned Project — Project Afterlife', 'header' => 'Upload Abandoned Project'])

@section('content')
<div class="max-w-4xl mx-auto py-6" x-data="{
    step: 1,
    selectedCategory: '',
    confirmed: false,
    nextStep() {
        if (this.step < 5) this.step++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    prevStep() {
        if (this.step > 1) this.step--;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}">
    <!-- Step Indicator Progress Bar -->
    <div class="mb-8">
        <div class="grid grid-cols-5 gap-2 text-center text-xs font-mono mb-3">
            <div :class="step >= 1 ? 'text-emerald-400 font-bold' : 'text-slate-400'">1. Basic Info</div>
            <div :class="step >= 2 ? 'text-emerald-400 font-bold' : 'text-slate-400'">2. Tech Stack</div>
            <div :class="step >= 3 ? 'text-emerald-400 font-bold' : 'text-slate-400'">3. Source Files</div>
            <div :class="step >= 4 ? 'text-emerald-400 font-bold' : 'text-slate-400'">4. Declaration</div>
            <div :class="step >= 5 ? 'text-emerald-400 font-bold' : 'text-slate-400'">5. Submit</div>
        </div>
        <div class="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
            <div class="h-full bg-emerald-500 transition-all duration-300" :style="'width: ' + (step * 20) + '%'"></div>
        </div>
    </div>

    <form action="{{ route('user.projects.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-800 bg-slate-900/60 p-8 shadow-xl">
        @csrf

        <!-- ================= STEP 1: BASIC INFORMATION ================= -->
        <div x-show="step === 1" class="space-y-6">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Step 1 — Basic Project Information</h2>
                <p class="text-xs text-slate-400 mt-1">Provide fundamental identifying details and the reason why development stalled.</p>
            </div>

            <div>
                <label for="title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Project Name *</label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}" placeholder="e.g. HyperLog: Distributed Logging Gateway" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label for="short_description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Short Description / Pitch *</label>
                <textarea id="short_description" name="short_description" rows="2" required placeholder="A brief 1-2 sentence summary of what this project does and its core architecture." class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Full Architecture & Description *</label>
                <textarea id="description" name="description" rows="6" required placeholder="Describe the original system design, working modules, dependencies, and what was left unfinished." class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Category *</label>
                    <select id="category_id" name="category_id" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="project_type" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Project Type *</label>
                    <select id="project_type" name="project_type" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        @foreach($projectTypes as $pt)
                            <option value="{{ $pt->value }}" {{ old('project_type') === $pt->value ? 'selected' : '' }}>{{ $pt->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="development_status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Development Stage *</label>
                    <select id="development_status" name="development_status" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        @foreach($devStatuses as $ds)
                            <option value="{{ $ds->value }}" {{ old('development_status') === $ds->value ? 'selected' : '' }}>{{ $ds->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="reason_for_abandonment" class="block text-xs font-semibold text-rose-300 uppercase tracking-wider font-mono">Reason for Abandonment *</label>
                <textarea id="reason_for_abandonment" name="reason_for_abandonment" rows="3" required placeholder="Why was this project abandoned? (e.g. loss of client funding, time constraints, tech stack pivot, complexity)." class="mt-1.5 block w-full rounded-lg border border-rose-900/60 bg-slate-950 px-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">{{ old('reason_for_abandonment') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="original_development_date" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Original Start Date</label>
                    <input type="date" id="original_development_date" name="original_development_date" value="{{ old('original_development_date') }}" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="last_development_date" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Last Active Dev Date</label>
                    <input type="date" id="last_development_date" name="last_development_date" value="{{ old('last_development_date') }}" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="button" @click="nextStep()" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                    Continue to Technical Stack &rarr;
                </button>
            </div>
        </div>

        <!-- ================= STEP 2: TECHNICAL STACK ================= -->
        <div x-show="step === 2" class="space-y-6" style="display: none;">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Step 2 — Normalized Technical Stack</h2>
                <p class="text-xs text-slate-400 mt-1">Select all programming languages, frameworks, databases, and libraries used by this software.</p>
            </div>

            <div class="space-y-6">
                @foreach($technologies as $type => $techList)
                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold mb-3">{{ ucfirst($type) }}</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach($techList as $tech)
                                <label class="flex items-center gap-2 rounded-lg border border-slate-800 bg-slate-900/60 p-2 text-xs text-slate-300 hover:border-slate-700 cursor-pointer">
                                    <input type="checkbox" name="technologies[]" value="{{ $tech->id }}" class="h-4 w-4 rounded border-slate-800 bg-slate-950 text-emerald-600 focus:ring-emerald-500">
                                    <span class="truncate">{{ $tech->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="button" @click="prevStep()" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-300 hover:text-white transition">
                    &larr; Back
                </button>
                <button type="button" @click="nextStep()" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                    Continue to Project Files &rarr;
                </button>
            </div>
        </div>

        <!-- ================= STEP 3: PROJECT FILES ================= -->
        <div x-show="step === 3" class="space-y-6" style="display: none;">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Step 3 — Project Files & Archives</h2>
                <p class="text-xs text-slate-400 mt-1">Attach source archives, database SQL dumps, README documentation, and screenshots. Files are stored securely in non-executable storage.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Source Code ZIP Archive</label>
                    <input type="file" name="source_zip" accept=".zip,.tar,.gz,.7z,.rar" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-950/60 file:text-emerald-300 hover:file:bg-emerald-900/60 file:cursor-pointer border border-slate-800 rounded-lg p-2 bg-slate-950">
                    <p class="text-[10px] text-slate-400 mt-1">Max 50MB. ZIP or TAR containing the uncompiled source code.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">README / Quickstart Document</label>
                    <input type="file" name="readme" accept=".md,.txt,.pdf" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer border border-slate-800 rounded-lg p-2 bg-slate-950">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Database SQL Dump (Optional)</label>
                    <input type="file" name="database_sql" accept=".sql,.txt,.dump,.zip" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer border border-slate-800 rounded-lg p-2 bg-slate-950">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Screenshots / Architecture Mockups</label>
                    <input type="file" name="screenshots[]" multiple accept="image/*" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer border border-slate-800 rounded-lg p-2 bg-slate-950">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="button" @click="prevStep()" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-300 hover:text-white transition">
                    &larr; Back
                </button>
                <button type="button" @click="nextStep()" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                    Continue to Declaration &rarr;
                </button>
            </div>
        </div>

        <!-- ================= STEP 4: OWNERSHIP DECLARATION ================= -->
        <div x-show="step === 4" class="space-y-6" style="display: none;">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Step 4 — Ownership & Submission Declaration</h2>
                <p class="text-xs text-slate-400 mt-1">To ensure open-source integrity, all uploaders must formally declare rights to the submitted software.</p>
            </div>

            <div class="rounded-xl border border-emerald-950/60 bg-emerald-950/20 p-5 text-xs text-emerald-200 leading-relaxed space-y-3">
                <p class="font-semibold">Legal & Open Transfer Affirmation:</p>
                <p>
                    "I confirm that I have the right to submit this software project to Project Afterlife and that the information provided is accurate. I understand that if approved and subsequently adopted by another developer, project ownership will be transferred via an administrator-approved transaction."
                </p>
                <div class="pt-2 text-[10px] text-emerald-400/80 font-mono">
                    User: {{ auth()->user()->name }} (ID: #{{ auth()->id() }}) • Timestamp: {{ now()->toIso8601String() }}
                </div>
            </div>

            <div class="flex items-start">
                <input id="ownership_confirmed" name="ownership_confirmed" type="checkbox" required x-model="confirmed" class="mt-0.5 h-4 w-4 rounded border-slate-800 bg-slate-950 text-emerald-600 focus:ring-emerald-500">
                <label for="ownership_confirmed" class="ml-2.5 block text-xs font-medium text-white">
                    I explicitly confirm the above ownership declaration and authorize Project Afterlife administrators to review and publish this software.
                </label>
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="button" @click="prevStep()" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-300 hover:text-white transition">
                    &larr; Back
                </button>
                <button type="button" @click="nextStep()" :disabled="!confirmed" :class="confirmed ? 'bg-emerald-600 hover:bg-emerald-500 text-white cursor-pointer' : 'bg-slate-800 text-slate-500 cursor-not-allowed'" class="rounded-lg px-5 py-2.5 text-xs font-semibold transition">
                    Review & Finalize &rarr;
                </button>
            </div>
        </div>

        <!-- ================= STEP 5: FINAL SUBMIT ================= -->
        <div x-show="step === 5" class="space-y-6" style="display: none;">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">Step 5 — Ready to Submit</h2>
                <p class="text-xs text-slate-400 mt-1">Once submitted, your project status will become <span class="font-mono text-amber-400 font-bold">PENDING_REVIEW</span>. The administrator will be notified immediately.</p>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5 text-xs text-slate-300 space-y-2">
                <div class="font-semibold text-white">What happens next?</div>
                <ul class="list-disc pl-5 space-y-1 text-slate-400 text-[11px]">
                    <li>An administrator reviews your project metadata, source files, and declaration.</li>
                    <li>If approved, the project is marked <span class="text-emerald-400 font-mono">AVAILABLE</span> and made searchable in the public repository.</li>
                    <li>If revision is needed, you will receive clear feedback in your dashboard to adjust the submission.</li>
                </ul>
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="button" @click="prevStep()" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-300 hover:text-white transition">
                    &larr; Back
                </button>
                <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition shadow-lg flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span>Submit for Administrator Verification</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
