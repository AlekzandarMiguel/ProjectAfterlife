@props(['files', 'title' => 'Preserved Archive Contents'])

@php
    $zipFiles = collect($files)->filter(fn($f) => !empty($f->file_tree_json) || $f->isZipArchive());
@endphp

@if($zipFiles->isNotEmpty())
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-xs theme-interactive-card" x-data="{
        search: '',
        activeTab: 0,
        expandedFolders: {},
        previewModal: false,
        previewFile: '',
        previewContent: '',
        previewLoading: false,
        previewError: '',
        toggleFolder(key) {
            this.expandedFolders[key] = !this.expandedFolders[key];
        },
        isExpanded(key) {
            return this.expandedFolders[key] !== false;
        },
        async openPreview(fileId, filePath, projectSlug) {
            this.previewFile = filePath;
            this.previewContent = '';
            this.previewError = '';
            this.previewLoading = true;
            this.previewModal = true;
            try {
                const url = '/explore/' + projectSlug + '/files/' + fileId + '/preview?path=' + encodeURIComponent(filePath);
                const res = await fetch(url);
                const data = await res.json();
                if (data.success && data.content !== undefined) {
                    this.previewContent = data.content;
                } else {
                    this.previewError = data.error || 'Could not load file preview.';
                }
            } catch (e) {
                this.previewError = 'Failed to load file preview.';
            } finally {
                this.previewLoading = false;
            }
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider font-mono">{{ $title }}</h3>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Inspect directories and source files preserved within the package.</p>
            </div>

            <!-- Integrity Hash & Search -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Filter files..." class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none w-36 sm:w-48">
                </div>
            </div>
        </div>

        @foreach($zipFiles as $index => $file)
            <div x-show="activeTab === {{ $index }}" class="mt-4 space-y-4">
                <!-- Archive Metadata Bar -->
                <div class="flex flex-wrap items-center justify-between gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800/80 text-xs font-mono">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            <span>{{ $file->file_name }}</span>
                        </span>
                        <span class="text-slate-500 dark:text-slate-400">({{ $file->formatted_size }})</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($file->security_status === 'clean')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                Verified Clean
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-950/50 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                Flagged Review
                            </span>
                        @endif

                        @if($file->sha256_hash)
                            <span class="hidden md:inline-flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800 truncate max-w-[200px]" title="SHA-256: {{ $file->sha256_hash }}">
                                <span>SHA:</span>
                                <span class="font-mono">{{ substr($file->sha256_hash, 0, 12) }}...</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tree View Container -->
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 p-4 max-h-96 overflow-y-auto font-mono text-xs text-slate-700 dark:text-slate-300">
                    @if(!empty($file->file_tree_json))
                        <div class="space-y-1">
                            @foreach($file->file_tree_json as $nodeKey => $node)
                                @if(is_array($node))
                                    @if(($node['type'] ?? '') === 'directory')
                                        <div x-data="{ open: true }" x-show="search === '' || JSON.stringify({{ json_encode($node) }}).toLowerCase().includes(search.toLowerCase())" class="space-y-1">
                                            <div @click="open = !open" class="flex items-center gap-1.5 py-1 px-1.5 rounded hover:bg-slate-200/60 dark:hover:bg-slate-800/60 cursor-pointer text-slate-800 dark:text-slate-200 font-semibold select-none">
                                                <svg class="h-3.5 w-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                                <span>{{ $node['name'] ?? $nodeKey }}/</span>
                                            </div>
                                            <div x-show="open" class="pl-5 border-l border-slate-200 dark:border-slate-800/80 ml-2 space-y-1">
                                                @foreach($node['children'] ?? [] as $childKey => $child)
                                                    @if(is_array($child))
                                                        @if(($child['type'] ?? '') === 'directory')
                                                            <div class="flex items-center gap-1.5 py-0.5 text-slate-700 dark:text-slate-300">
                                                                <svg class="h-3.5 w-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                                                <span>{{ $child['name'] ?? $childKey }}/</span>
                                                            </div>
                                                        @else
                                                            <div x-show="search === '' || '{{ strtolower($child['name'] ?? '') }}'.includes(search.toLowerCase())" @click="openPreview({{ $file->id }}, '{{ ($node['name'] ?? '') . '/' . ($child['name'] ?? $childKey) }}', '{{ $file->project->slug ?? '' }}')" class="flex items-center justify-between py-1 px-1.5 rounded hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-700 dark:hover:text-emerald-300 transition cursor-pointer group select-none">
                                                                <div class="flex items-center gap-1.5">
                                                                    <svg class="h-3.5 w-3.5 text-slate-400 group-hover:text-emerald-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                                    <span>{{ $child['name'] ?? $childKey }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 font-bold transition">Preview</span>
                                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ round(($child['size'] ?? 0) / 1024, 1) }} KB</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div x-show="search === '' || '{{ strtolower($node['name'] ?? '') }}'.includes(search.toLowerCase())" @click="openPreview({{ $file->id }}, '{{ $node['name'] ?? $nodeKey }}', '{{ $file->project->slug ?? '' }}')" class="flex items-center justify-between py-1 px-1.5 rounded hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-700 dark:hover:text-emerald-300 transition cursor-pointer group select-none">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5 text-slate-400 group-hover:text-emerald-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <span>{{ $node['name'] ?? $nodeKey }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 font-bold transition">Preview</span>
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ round(($node['size'] ?? 0) / 1024, 1) }} KB</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-slate-500 dark:text-slate-400">
                            <p>Direct archive inspection index is being compiled.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- In-Browser File Preview Modal -->
        <div x-show="previewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
            <div class="w-full max-w-3xl rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4 max-h-[85vh] flex flex-col" @click.outside="previewModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-mono text-xs font-bold text-slate-900 dark:text-white" x-text="previewFile"></span>
                    </div>
                    <button type="button" @click="previewModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto font-mono text-xs p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200">
                    <div x-show="previewLoading" class="py-8 text-center text-slate-400 font-mono">Loading archive file contents...</div>
                    <div x-show="previewError" class="py-8 text-center text-rose-400 font-mono" x-text="previewError"></div>
                    <pre x-show="!previewLoading && !previewError" class="whitespace-pre-wrap font-mono leading-relaxed" x-text="previewContent"></pre>
                </div>

                <div class="flex items-center justify-between shrink-0 pt-2 text-[11px] font-mono text-slate-500 dark:text-slate-400">
                    <span>Safe In-Browser Sandbox Preview (Max 256KB)</span>
                    <button type="button" @click="previewModal = false" class="px-4 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">Close</button>
                </div>
            </div>
        </div>

    </div>
@endif
