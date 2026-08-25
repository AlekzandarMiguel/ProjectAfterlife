@extends('layouts.app', ['title' => $user->name . ' — Profile', 'header' => 'Developer Profile'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <img class="h-16 w-16 rounded-full bg-slate-800 ring-2 ring-emerald-500/50" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            <div>
                <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                <div class="text-xs text-slate-400 font-mono">@ {{ $user->username ?? 'user' }} • Member since {{ $user->created_at->format('M Y') }}</div>
                @if($user->profile?->location)
                    <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>{{ $user->profile->location }}</span>
                    </div>
                @endif
            </div>
        </div>

        <a href="{{ route('user.profile.edit') }}" class="rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
            Edit Settings
        </a>
    </div>

    @if($user->profile?->bio)
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6">
            <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-2">Biography</h3>
            <p class="text-xs text-slate-300 leading-relaxed">{{ $user->profile->bio }}</p>
        </div>
    @endif

    <!-- Skills Badge Cloud -->
    @if(!empty($user->profile?->skills))
        <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6">
            <h3 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-semibold mb-3">Skill Set</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($user->profile->skills as $s)
                    <span class="rounded-lg bg-slate-950 border border-slate-800 px-3 py-1 text-xs text-slate-200 font-mono">{{ $s }}</span>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
