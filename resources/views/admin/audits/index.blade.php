@extends('layouts.admin', ['title' => 'Audit Logs — Project Afterlife', 'header' => 'System Audit Logs'])

@section('content')
<div class="space-y-6">
    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">System Audit Log Ledger</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tamper-evident record of all critical system actions, status transitions, and authentication events.</p>
    </div>

    <!-- Audit Table -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-950 font-mono uppercase text-[10px] text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-3">Action</th>
                    <th class="px-6 py-3">Actor</th>
                    <th class="px-6 py-3">Target Entity</th>
                    <th class="px-6 py-3">IP Address</th>
                    <th class="px-6 py-3">Timestamp</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-mono text-[11px]">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/60 transition">
                        <td class="px-6 py-3.5 font-bold text-purple-400">{{ $log->action }}</td>
                        <td class="px-6 py-3.5 text-slate-800 dark:text-slate-200">{{ $log->user->name ?? 'System / Anonymous' }}</td>
                        <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400 truncate max-w-[200px]">{{ $log->entity_type ? class_basename($log->entity_type) . ' #' . $log->entity_id : '—' }}</td>
                        <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400">{{ $log->ip_address }}</td>
                        <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $logs->links() }}</div>
</div>
@endsection