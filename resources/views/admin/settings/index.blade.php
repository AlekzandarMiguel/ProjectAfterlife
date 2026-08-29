@extends('layouts.admin', ['title' => 'Platform Settings — Project Afterlife', 'header' => 'Platform Settings'])

@section('content')
<div class="max-w-3xl mx-auto py-6 space-y-8">
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-8 space-y-6">
        <h2 class="text-base font-bold text-white tracking-tight">Recovery & Inactivity Configuration</h2>
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-slate-300">Inactivity Threshold (Days) *</label>
                <input type="number" name="inactivity_threshold_days" value="{{ old('inactivity_threshold_days', $settings['inactivity_threshold_days']) }}" min="7" max="180" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                <p class="text-[10px] text-slate-400 mt-1">Number of days without recovery task or version activity before project is flagged for inactivity monitoring.</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300">Max Archive Upload Size (MB) *</label>
                <input type="number" name="max_upload_size_mb" value="{{ old('max_upload_size_mb', $settings['max_upload_size_mb']) }}" min="10" max="200" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
