@extends('layouts.app', ['title' => 'Versions: ' . $project->title, 'header' => 'Version Management'])

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-8" x-data="{ openVersionModal: false }">
    <div class="flex items-center justify-between pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <span class="text-xs font-mono text-slate-500 dark:text-slate-400">Immutable Release Log</span>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">{{ $project->title }}</h1>
        </div>

        <button type="button" @click="openVersionModal = true" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Tag New Version Release</span>
        </button>
    </div>

    <!-- Versions List -->
    <div class="space-y-6">
        @foreach($project->versions as $version)
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <span class="rounded bg-emerald-500/10 border border-emerald-500/30 px-2.5 py-1 font-mono text-xs font-bold text-emerald-400">
                            {{ $version->version_number }}
                        </span>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $version->title }}</h3>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                        Released by {{ $version->uploader->name }} on {{ $version->created_at->format('M d, Y') }}
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-lg border border-slate-200 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-300 font-mono whitespace-pre-line leading-relaxed">
                    {{ $version->release_notes }}
                </div>

                @if($version->files->count() > 0)
                    <div class="pt-2 flex flex-wrap gap-2">
                        @foreach($version->files as $f)
                            <a href="{{ route('explore.files.download', [$project, $f]) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-1.5 text-xs text-slate-700 dark:text-slate-300 hover:text-white transition">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                <span>{{ $f->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Release Version Modal -->
    <div x-show="openVersionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openVersionModal = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Release New Version</h3>
            <form action="{{ route('user.versions.store', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="version_number" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Version Tag *</label>
                        <input type="text" id="version_number" name="version_number" required placeholder="e.g. v1.1.0" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono">
                    </div>
                    <div>
                        <label for="v_title" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Release Title *</label>
                        <input type="text" id="v_title" name="title" required placeholder="e.g. Auth Refactor & UI Redesign" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label for="release_notes" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Release Notes / Changelog *</label>
                    <textarea id="release_notes" name="release_notes" rows="4" required placeholder="- Fixed SQL injection in user query&#10;- Upgraded Laravel framework&#10;- Added unit test suite" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Updated Source Code ZIP</label>
                    <input type="file" name="source_zip" accept=".zip,.tar,.gz,.7z" class="mt-1 block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-100 dark:bg-slate-800 file:text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-lg p-2 bg-slate-50 dark:bg-slate-950">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openVersionModal = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white transition">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">Publish Release</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection