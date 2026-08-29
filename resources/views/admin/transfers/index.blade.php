@extends('layouts.admin', ['title' => 'Ownership Transfers Ledger — Project Afterlife', 'header' => 'Ownership Transfers Ledger'])

@section('content')
<div class="space-y-6" x-data="{
    openModal: false,
    selected: null,
    showProof(transferData) {
        this.selected = transferData;
        this.openModal = true;
    }
}">
    <div class="pb-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Immutable Ownership Transfer Ledger</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Permanent record of all software ownership transfers approved by system administrators.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-xs font-mono font-bold text-emerald-800 dark:text-emerald-300">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span>Cryptographic Proof Active</span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-xs theme-interactive-card overflow-hidden">
        <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-950 font-mono uppercase text-[10px] text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-3.5 w-24">ID</th>
                    <th class="px-4 py-3.5">Project</th>
                    <th class="px-4 py-3.5">Chain of Custody (Author &rarr; Adopter)</th>
                    <th class="px-4 py-3.5 hidden md:table-cell">Authorized By</th>
                    <th class="px-4 py-3.5 w-28 text-center">Status</th>
                    <th class="px-4 py-3.5 text-right w-36">Proof Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($transfers as $t)
                    @php
                        $zipFile = $t->project->files->firstWhere('file_type', \App\Enums\FileType::SOURCE_CODE_ZIP) ?? $t->project->files->first();
                        $transferPayload = [
                            'id' => $t->id,
                            'project_title' => $t->project->title,
                            'project_url' => route('explore.show', $t->project),
                            'previous_owner' => $t->previousOwner->name ?? 'Verified Author',
                            'new_owner' => $t->newOwner->name ?? 'Maintainer',
                            'admin_approver' => $t->adminApprover->name ?? 'Administrator',
                            'license' => $t->project->license_type ?? 'Open-Source Grant',
                            'transferred_at' => $t->transferred_at->format('M d, Y H:i:s T'),
                            'created_at' => $t->project->created_at->format('M d, Y'),
                            'sha256' => $zipFile?->sha256_hash ?? 'N/A',
                            'status' => strtoupper($t->transfer_status),
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/60 transition">
                        <td class="px-4 py-3.5 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            #TRF-{{ $t->id }}
                        </td>
                        <td class="px-4 py-3.5">
                            <a href="{{ route('explore.show', $t->project) }}" class="font-bold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition block">
                                {{ $t->project->title }}
                            </a>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $t->project->category->name ?? 'General' }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-slate-600 dark:text-slate-400 font-medium">{{ $t->previousOwner->name ?? 'Author' }}</span>
                                <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                <span class="text-emerald-700 dark:text-emerald-400 font-bold">{{ $t->newOwner->name ?? 'Adopter' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 hidden md:table-cell">
                            <div class="font-mono text-[11px] text-purple-700 dark:text-purple-300 font-medium">{{ $t->adminApprover->name ?? 'Administrator' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $t->transferred_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                {{ strtoupper($t->transfer_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" @click="showProof({{ json_encode($transferPayload) }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-700/80 text-xs font-mono font-bold text-emerald-800 dark:text-emerald-300 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 transition shadow-xs cursor-pointer whitespace-nowrap">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                <span>Inspect</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 font-mono text-xs">
                            No ownership transfers executed yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $transfers->links() }}</div>

    <!-- Interactive Proof Certificate Modal -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-2xl rounded-2xl border-2 border-emerald-500/40 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-5 relative" @click.outside="openModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Ownership Provenance Certificate</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">Ledger Record: <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="'#TRF-' + selected?.id"></span></p>
                    </div>
                </div>
                <button type="button" @click="openModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400">Target Project</span>
                    <div class="font-bold text-slate-900 dark:text-white" x-text="selected?.project_title"></div>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400">Legal License Grant</span>
                    <div class="font-bold text-slate-900 dark:text-white" x-text="selected?.license"></div>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400">Original Author</span>
                    <div class="font-bold text-slate-900 dark:text-white" x-text="selected?.previous_owner"></div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono" x-text="'Declared: ' + selected?.created_at"></div>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400">New Approved Maintainer</span>
                    <div class="font-bold text-emerald-700 dark:text-emerald-400" x-text="selected?.new_owner"></div>
                    <div class="text-[10px] text-purple-600 dark:text-purple-400 font-mono" x-text="'Authorized: ' + selected?.admin_approver"></div>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-1.5">
                <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400">Cryptographic Archive Hash (SHA-256)</span>
                <div class="text-[11px] font-mono text-slate-800 dark:text-slate-200 select-all break-all p-2 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" x-text="selected?.sha256"></div>
                <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 font-mono pt-1">
                    <span>Execution Timestamp: <strong class="text-slate-900 dark:text-white" x-text="selected?.transferred_at"></strong></span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Immutable Ledger Stored</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a :href="selected?.project_url" target="_blank" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-emerald-600 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-xs">
                    <span>View Public Certificate</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
