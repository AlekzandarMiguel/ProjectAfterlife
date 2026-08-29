@extends('layouts.admin', ['title' => 'Platform Settings — Admin Console', 'header' => 'Platform Settings & Governance'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header Info Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Platform Configuration & System Health</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Configure automated recovery policies, file thresholds, and review runtime infrastructure telemetry.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-100 dark:bg-emerald-950/40 px-3 py-1 text-xs font-bold text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800/50">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>System Operational</span>
            </span>
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Recovery & Storage Policies</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Adjust operational thresholds for project abandonment tracking and secure file storage limits.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                        Inactivity Monitoring Threshold (Days) *
                    </label>
                    <input type="number" 
                           name="inactivity_threshold_days" 
                           value="{{ old('inactivity_threshold_days', $settings['inactivity_threshold_days']) }}" 
                           min="7" 
                           max="180" 
                           required 
                           class="mt-1.5 block w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:border-purple-500 dark:focus:border-purple-400 focus:ring-1 focus:ring-purple-500 transition shadow-xs">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                        Days without a task update or release before an adopted project is flagged for recovery re-abandonment.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                        Max Project Archive Size (MB) *
                    </label>
                    <input type="number" 
                           name="max_upload_size_mb" 
                           value="{{ old('max_upload_size_mb', $settings['max_upload_size_mb']) }}" 
                           min="10" 
                           max="200" 
                           required 
                           class="mt-1.5 block w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:border-purple-500 dark:focus:border-purple-400 focus:ring-1 focus:ring-purple-500 transition shadow-xs">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                        Maximum allowed compressed source archive size (.zip) for intake submissions and version releases.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Changes are recorded to the system audit ledger.
                </div>
                <button type="submit" class="rounded-xl bg-purple-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-purple-500 transition shadow-sm flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span>Save Platform Settings</span>
                </button>
            </div>
        </form>
    </div>

    <!-- System Infrastructure Diagnostics -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Runtime Diagnostics & Environment</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Live configuration parameters reported by the underlying application stack.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">Framework</div>
                <div class="text-sm font-bold text-slate-900 dark:text-white">Laravel {{ $settings['laravel_version'] }}</div>
            </div>

            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">PHP Runtime</div>
                <div class="text-sm font-bold text-slate-900 dark:text-white">PHP {{ $settings['php_version'] }}</div>
            </div>

            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">Environment</div>
                <div class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase font-mono">{{ $settings['app_env'] }}</div>
            </div>

            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">Database Engine</div>
                <div class="text-sm font-bold text-slate-900 dark:text-white font-mono">{{ strtoupper($settings['db_driver']) }}</div>
            </div>

            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">Google OAuth</div>
                <div class="text-sm font-bold {{ $settings['google_oauth_active'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500' }}">
                    {{ $settings['google_oauth_active'] ? 'Enabled & Verified' : 'Disabled' }}
                </div>
            </div>

            <div class="rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-4 space-y-1">
                <div class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 font-bold">OTP Password Reset</div>
                <div class="text-sm font-bold text-emerald-700 dark:text-emerald-400">6-Digit Email Code</div>
            </div>
        </div>
    </div>
</div>
@endsection
