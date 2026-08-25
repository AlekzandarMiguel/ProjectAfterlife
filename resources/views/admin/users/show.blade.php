@extends('layouts.admin', ['title' => $user->name . ' — User Audit', 'header' => 'User Detail Audit'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img class="h-14 w-14 rounded-full bg-slate-800 ring-2 ring-purple-500/40" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            <div>
                <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                <div class="text-xs text-slate-400 font-mono">{{ $user->email }} • Role: {{ strtoupper($user->role->value) }} • Status: {{ strtoupper($user->status->value) }}</div>
            </div>
        </div>

        @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg {{ $user->status->value === 'active' ? 'bg-rose-600 hover:bg-rose-500' : 'bg-emerald-600 hover:bg-emerald-500' }} px-3.5 py-2 text-xs font-semibold text-white transition">
                    {{ $user->status->value === 'active' ? 'Suspend Account' : 'Activate Account' }}
                </button>
            </form>
        @endif
    </div>

    <!-- User Projects -->
    <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 space-y-4">
        <h2 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold">Owned & Uploaded Projects ({{ $user->ownedProjects->count() }})</h2>
        <div class="divide-y divide-slate-800">
            @forelse($user->ownedProjects as $p)
                <div class="py-2.5 flex items-center justify-between text-xs">
                    <span class="text-white font-medium">{{ $p->title }}</span>
                    <x-status-badge :status="$p->status" />
                </div>
            @empty
                <p class="text-xs text-slate-400">No projects owned by this user.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
