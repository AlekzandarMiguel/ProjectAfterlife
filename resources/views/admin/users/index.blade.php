@extends('layouts.admin', ['title' => 'Users Management — Project Afterlife', 'header' => 'Users Directory'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-white tracking-tight">User Directory</h2>
            <p class="text-xs text-slate-400 mt-0.5">View and manage all registered developer accounts on the platform.</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="rounded-xl border border-slate-800 bg-slate-900/40 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-800 text-left text-xs text-slate-300">
            <thead class="bg-slate-950 font-mono uppercase text-[10px] text-slate-400">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Projects</th>
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-900/60 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img class="h-8 w-8 rounded-full bg-slate-800 ring-1 ring-slate-700" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                            <div>
                                <div class="font-semibold text-white">{{ $user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $user->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold {{ $user->role->value === 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/30' : 'bg-slate-800 text-slate-300' }}">
                                {{ strtoupper($user->role->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold {{ $user->status->value === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                {{ strtoupper($user->status->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px]">
                            Owned: {{ $user->ownedProjects->count() }} • Uploaded: {{ $user->uploadedProjects->count() }}
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-mono text-[11px]">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-purple-400 hover:underline">Inspect</a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[11px] {{ $user->status->value === 'active' ? 'text-rose-400 hover:underline' : 'text-emerald-400 hover:underline' }}">
                                        {{ $user->status->value === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $users->links() }}</div>
</div>
@endsection
