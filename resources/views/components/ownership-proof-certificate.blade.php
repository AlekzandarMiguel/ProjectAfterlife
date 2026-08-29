@props(['project'])

@php
    $latestTransfer = $project->ownershipTransfers->first();
    $primaryZip = $project->files->firstWhere('file_type', \App\Enums\FileType::SOURCE_CODE_ZIP) ?? $project->files->first();
@endphp

<div class="rounded-2xl border-2 border-emerald-500/40 bg-gradient-to-br from-emerald-50/80 via-white to-slate-50 dark:from-emerald-950/20 dark:via-slate-900/60 dark:to-slate-950 p-6 shadow-sm relative overflow-hidden theme-interactive-card">
    <!-- Ambient Badge Stamp in Top Right -->
    <div class="absolute -right-8 -top-8 w-28 h-28 bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-emerald-200 dark:border-emerald-900/50">
        <div class="flex items-center gap-3.5">
            <x-official-seal size="w-12 h-12 shrink-0" />
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider font-mono">Proof of Legitimate Ownership & Provenance</h3>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Audited chain of custody and legal declaration verified by Project Afterlife.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @php
                $canViewCertificate = auth()->check() && (
                    auth()->user()->isAdmin() ||
                    (int) auth()->id() === (int) $project->original_owner_id ||
                    (int) auth()->id() === (int) $project->owner_id ||
                    $project->adoptionRequests()->where('user_id', auth()->id())->exists() ||
                    $project->ownershipTransfers()->where('new_owner_id', auth()->id())->exists()
                );
            @endphp

            @if($canViewCertificate)
                <a href="{{ route('explore.certificate', $project) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-950 border border-emerald-300 dark:border-emerald-700 text-xs font-mono font-bold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-600 hover:text-white transition shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    <span>Print Official Certificate</span>
                </a>
            @endif

            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                <span>CERTIFIED & AUDITED</span>
            </span>
        </div>
    </div>

    <!-- Certificate Details Grid -->
    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
        <!-- 1. Original Creator -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-950/60 p-3.5 space-y-1">
            <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400 block">1. Original Copyright Author</span>
            <div class="font-bold text-slate-900 dark:text-white">{{ $project->originalOwner->name ?? 'Verified Author' }}</div>
            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Declared: {{ $project->created_at->format('M d, Y') }}</div>
        </div>

        <!-- 2. Legal Consent & License -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-950/60 p-3.5 space-y-1">
            <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400 block">2. Legal Transfer Grant</span>
            <div class="font-bold text-slate-900 dark:text-white">{{ $project->license_type ?? 'Open-Source Grant' }}</div>
            <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-mono">Consent: Confirmed</div>
        </div>

        <!-- 3. Current Adopted Owner -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-950/60 p-3.5 space-y-1">
            <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400 block">3. Authorized Maintainer</span>
            <div class="font-bold text-slate-900 dark:text-white">{{ $project->owner->name ?? 'Maintainer' }}</div>
            @if($latestTransfer)
                <div class="text-[11px] text-purple-600 dark:text-purple-400 font-mono">Handover: {{ $latestTransfer->transferred_at->format('M d, Y') }}</div>
            @else
                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Original Maintainer</div>
            @endif
        </div>

        <!-- 4. Governance Approver -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-950/60 p-3.5 space-y-1">
            <span class="text-[10px] font-mono uppercase font-bold text-slate-500 dark:text-slate-400 block">4. Administrator Review</span>
            @if($latestTransfer)
                <div class="font-bold text-slate-900 dark:text-white">{{ $latestTransfer->adminApprover->name ?? 'Platform Administrator' }}</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Record: #TRF-{{ $latestTransfer->id }}</div>
            @else
                <div class="font-bold text-slate-900 dark:text-white">Admin Verified</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Preservation Active</div>
            @endif
        </div>
    </div>

    <!-- Cryptographic Proof Footer Bar -->
    @if($primaryZip && $primaryZip->sha256_hash)
        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] font-mono text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-2 truncate">
                <span class="font-bold text-slate-700 dark:text-slate-300">Cryptographic Archive Hash (SHA-256):</span>
                <span class="text-slate-800 dark:text-slate-200 font-mono select-all truncate bg-slate-100 dark:bg-slate-950 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800">{{ $primaryZip->sha256_hash }}</span>
            </div>
            <div class="text-emerald-700 dark:text-emerald-400 font-bold shrink-0">
                Immutable Ledger Verified
            </div>
        </div>
    @endif
</div>
