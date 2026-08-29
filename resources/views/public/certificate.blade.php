<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-200">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate of Software Provenance — {{ $project->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: #ffffff !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .certificate-frame { border: 4px double #065f46 !important; box-shadow: none !important; margin: 0 auto !important; max-width: 100% !important; }
        }
        .cert-serif {
            font-family: "Georgia", "Baskerville", "Times New Roman", serif;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased text-slate-900 p-4 sm:p-10 flex flex-col items-center justify-center">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="no-print w-full max-w-4xl flex items-center justify-between gap-4 mb-6">
        <a href="{{ route('explore.show', $project) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-slate-900 transition font-mono">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span>Back to Project Explorer</span>
        </a>

        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-800 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm cursor-pointer font-mono">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            <span>Print / Save as PDF</span>
        </button>
    </div>

    <!-- Authentic Document Frame -->
    <div class="certificate-frame w-full max-w-4xl bg-[#fdfbf7] text-slate-900 p-8 sm:p-14 shadow-2xl border-4 border-emerald-900 relative space-y-8" style="outline: 2px solid #065f46; outline-offset: 6px;">
        
        <!-- Header Attestation -->
        <div class="text-center space-y-2 border-b-2 border-emerald-900/30 pb-6">
            <div class="cert-serif text-xs uppercase tracking-[0.25em] text-emerald-900 font-bold">
                Project Afterlife &bull; Software Preservation Registry
            </div>
            <h1 class="cert-serif text-2xl sm:text-4xl font-extrabold uppercase tracking-tight text-emerald-950">
                Certificate of Software Provenance
            </h1>
            <div class="cert-serif text-xs italic text-slate-600">
                Formal Attestation of Authorship, License Terms, and Custody Transfer
            </div>
        </div>

        <!-- Main Body Certification Phrasing -->
        <div class="cert-serif text-center space-y-6 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed text-slate-800">
            <p class="uppercase text-xs tracking-widest text-slate-500 font-bold font-sans">
                This document certifies that the open-source repository entitled
            </p>

            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">
                {{ $project->title }}
            </div>

            <p class="text-xs sm:text-sm text-slate-700 italic max-w-xl mx-auto">
                "{{ $project->short_description }}"
            </p>

            <div class="py-2 text-xs sm:text-sm text-slate-800 leading-loose">
                Originally authored and copyrighted by <strong class="text-emerald-950 font-bold">{{ $project->originalOwner->name ?? 'Verified Author' }}</strong> 
                and preserved under the terms of the <strong class="text-emerald-950 font-bold">{{ $project->license_type ?? 'Open-Source License' }}</strong>.
                @if($latestTransfer)
                    <br>
                    Custody, maintenance rights, and project stewardship were formally transferred unto 
                    <strong class="text-emerald-950 font-bold">{{ $project->owner->name ?? 'Approved Maintainer' }}</strong> 
                    on <strong class="text-slate-900">{{ $latestTransfer->transferred_at->format('F d, Y') }}</strong>.
                @else
                    <br>
                    Currently preserved in the official archive under active stewardship.
                @endif
            </div>
        </div>

        <!-- Formal Ledger Record Table -->
        <div class="border border-emerald-900/30 bg-white p-5 text-xs text-slate-800 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 cert-serif">
                <div>
                    <span class="text-slate-500 uppercase text-[10px] font-sans font-bold block">Registry Reference</span>
                    <span class="font-mono font-bold text-emerald-900">#TRF-{{ $latestTransfer->id ?? $project->id }}</span>
                </div>
                <div>
                    <span class="text-slate-500 uppercase text-[10px] font-sans font-bold block">Registration Date</span>
                    <span class="font-bold text-slate-900">{{ $project->created_at->format('F d, Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-500 uppercase text-[10px] font-sans font-bold block">Legal Declaration Consent</span>
                    <span class="font-bold text-emerald-900">Confirmed & Recorded</span>
                </div>
                <div>
                    <span class="text-slate-500 uppercase text-[10px] font-sans font-bold block">Preservation Status</span>
                    <span class="font-bold uppercase text-slate-900">{{ $project->status->label() }}</span>
                </div>
            </div>

            @if($sourceZip && $sourceZip->sha256_hash)
                <div class="pt-2 border-t border-slate-200">
                    <span class="text-slate-500 uppercase text-[10px] font-sans font-bold block">Cryptographic Checksum (SHA-256)</span>
                    <span class="font-mono text-[11px] text-slate-900 break-all select-all block mt-0.5">
                        {{ $sourceZip->sha256_hash }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Classical Signatures and Central Seal -->
        <div class="pt-6 border-t-2 border-emerald-900/30 grid grid-cols-1 sm:grid-cols-3 items-center gap-6 text-center">
            <!-- Left Signature Line -->
            <div class="space-y-1">
                <div class="cert-serif font-bold text-slate-900 text-sm italic">{{ $project->originalOwner->name ?? 'Verified Author' }}</div>
                <div class="w-44 mx-auto border-b border-slate-700"></div>
                <div class="text-[10px] uppercase font-sans font-bold tracking-wider text-slate-500">Original Author</div>
            </div>

            <!-- Central Classic Notary Seal -->
            <div class="flex justify-center">
                <x-official-seal size="w-28 h-28" />
            </div>

            <!-- Right Signature Line -->
            <div class="space-y-1">
                <div class="cert-serif font-bold text-slate-900 text-sm italic">{{ $latestTransfer->adminApprover->name ?? 'Project Afterlife Authority' }}</div>
                <div class="w-44 mx-auto border-b border-slate-700"></div>
                <div class="text-[10px] uppercase font-sans font-bold tracking-wider text-slate-500">Authorized Administrator</div>
            </div>
        </div>

        <!-- Footer Metadata -->
        <div class="text-center font-mono text-[10px] text-slate-400 border-t border-slate-200 pt-3">
            Official Document Record &bull; Generated: {{ now()->format('Y-m-d H:i:s T') }} &bull; Project Afterlife Governance
        </div>
    </div>
</body>
</html>
