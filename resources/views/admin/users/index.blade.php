@extends('layouts.admin', ['title' => 'Users Management — Project Afterlife', 'header' => 'Users Directory'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">User Directory & Verification</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage developer accounts, review pending signups, and control permissions.</p>
        </div>

        <!-- Create User Button -->
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-950/50 transition duration-150">
                <span>+</span>
                <span>Create User / Admin</span>
            </a>
        </div>
    </div>

    <div class="flex items-center justify-between gap-4 pb-2">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !request('status') ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-white' }} transition">
                All Users
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-lg shadow-amber-900/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-white' }} transition">
                <span>Pending Verification</span>
                @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full bg-amber-400 text-slate-950 font-bold text-[10px]">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-white' }} transition">
                Active
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'suspended']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === 'suspended' ? 'bg-rose-600 text-white shadow-lg shadow-rose-900/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-white' }} transition">
                Suspended
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 overflow-hidden shadow-xl">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-950 font-mono uppercase text-[10px] text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Projects</th>
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3 text-right">Verification & Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/60 transition {{ $user->isPending() ? 'bg-amber-950/10' : '' }}">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center font-bold text-slate-900 dark:text-white text-xs shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->isPending())
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-mono bg-amber-500/20 text-amber-300 border border-amber-500/30">Needs Review</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $user->email }} • {{ '@' . $user->username }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold {{ $user->role->value === 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                                {{ strtoupper($user->role->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status->value === 'pending')
                                <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                    PENDING APPROVAL
                                </span>
                            @elseif($user->status->value === 'active')
                                <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    ACTIVE
                                </span>
                            @else
                                <span class="rounded px-2 py-0.5 text-[10px] font-mono font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                    SUSPENDED
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px]">
                            Owned: {{ $user->ownedProjects->count() }} • Uploaded: {{ $user->uploadedProjects->count() }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono text-[11px]">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($user->isPending())
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-[11px] shadow-sm transition">
                                        ✓ Approve Account
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline" onsubmit="return confirm('Reject and suspend this account?');">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded bg-rose-600/20 hover:bg-rose-600/40 text-rose-300 font-medium text-[11px] border border-rose-600/30 transition">
                                        Decline
                                    </button>
                                </form>
                            @else
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
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No users found matching this criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $users->links() }}</div>
</div>
@endsection