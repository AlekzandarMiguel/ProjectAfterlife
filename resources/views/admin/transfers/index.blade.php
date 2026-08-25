@extends('layouts.admin', ['title' => 'Ownership Transfers Ledger — Project Afterlife', 'header' => 'Ownership Transfers Ledger'])

@section('content')
<div class="space-y-6">
    <div class="pb-4 border-b border-slate-800">
        <h2 class="text-lg font-bold text-white tracking-tight">Immutable Ownership Transfer Ledger</h2>
        <p class="text-xs text-slate-400 mt-0.5">Permanent record of all software ownership transfers approved by system administrators.</p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900/40 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-800 text-left text-xs text-slate-300">
            <thead class="bg-slate-950 font-mono uppercase text-[10px] text-slate-400">
                <tr>
                    <th class="px-6 py-3">Project</th>
                    <th class="px-6 py-3">Previous Owner</th>
                    <th class="px-6 py-3">New Owner (Adopter)</th>
                    <th class="px-6 py-3">Authorized By</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Transferred At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($transfers as $t)
                    <tr class="hover:bg-slate-900/60 transition">
                        <td class="px-6 py-4 font-semibold text-white">
                            {{ $t->project->title }}
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $t->previousOwner->name }}
                        </td>
                        <td class="px-6 py-4 text-emerald-400 font-medium">
                            {{ $t->newOwner->name }}
                        </td>
                        <td class="px-6 py-4 text-purple-300 font-mono text-[11px]">
                            {{ $t->adminApprover->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded bg-emerald-500/10 text-emerald-400 px-2 py-0.5 font-mono text-[10px]">
                                {{ strtoupper($t->transfer_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-mono text-[11px]">
                            {{ $t->transferred_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $transfers->links() }}</div>
</div>
@endsection
