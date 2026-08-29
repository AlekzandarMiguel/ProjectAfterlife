@extends('layouts.admin', ['title' => $user->name . ' — User Security & Audit', 'header' => 'User Security & Role Management'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ showPromoteModal: false }">

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-950/70 border border-emerald-500/40 p-4 text-sm text-emerald-200 flex items-center gap-3 shadow-lg">
            <svg class="h-5 w-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl bg-rose-950/70 border border-rose-500/40 p-4 text-sm text-rose-200 flex items-center gap-3 shadow-lg">
            <svg class="h-5 w-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl bg-rose-950/70 border border-rose-500/40 p-4 text-sm text-rose-200 space-y-1 shadow-lg">
            <div class="font-bold">Security Verification Failed:</div>
            <ul class="list-disc list-inside text-xs text-rose-300">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- USER PROFILE CARD -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl">
        <div class="flex items-center gap-4">
            <img class="h-16 w-16 rounded-full bg-slate-100 dark:bg-slate-800 ring-2 {{ $user->isAdmin() ? 'ring-purple-500/60' : 'ring-emerald-500/60' }}" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                    @if($user->isAdmin())
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-900/60 text-purple-200 border border-purple-500/40 uppercase font-mono">Administrator</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-900/60 text-emerald-200 border border-emerald-500/40 uppercase font-mono">Developer</span>
                    @endif
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-1">
                    {{ $user->email }} &bull; @<span>{{ $user->username ?? 'no-username' }}</span> &bull; Status: <span class="uppercase font-bold {{ $user->isActive() ? 'text-emerald-400' : 'text-amber-400' }}">{{ $user->status->value }}</span>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        @if($user->id !== auth()->id())
            <div class="flex items-center gap-3">
                <button type="button" @click="showPromoteModal = true" class="rounded-xl border border-purple-500/40 bg-purple-950/40 hover:bg-purple-900/60 px-4 py-2 text-xs font-bold text-purple-200 transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span>Change Role</span>
                </button>

                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this user status?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-xl {{ $user->status->value === 'active' ? 'bg-rose-950/40 border border-rose-500/40 hover:bg-rose-900/60 text-rose-200' : 'bg-emerald-950/40 border border-emerald-500/40 hover:bg-emerald-900/60 text-emerald-200' }} px-4 py-2 text-xs font-bold transition shadow-sm">
                        {{ $user->status->value === 'active' ? 'Suspend Account' : 'Activate Account' }}
                    </button>
                </form>
            </div>
        @else
            <span class="text-xs text-purple-400 font-mono italic">Current Active Session (Your Account)</span>
        @endif
    </div>

    <!-- SECURITY ROLE ELEVATION MODAL -->
    <div x-show="showPromoteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl border border-purple-500/40 bg-white dark:bg-slate-900 p-8 shadow-2xl space-y-6" @click.outside="showPromoteModal = false">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <span>Role Elevation Security Gate</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Adjust system privileges for <strong class="text-slate-900 dark:text-white">{{ $user->name }}</strong> ({{ $user->email }}).</p>
                </div>
                <button type="button" @click="showPromoteModal = false" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:text-white">&times;</button>
            </div>

            <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Target Role Selection -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Target Privilege Role <span class="text-rose-400">*</span></label>
                    <select id="role" name="role" required class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 py-2.5 px-3 text-xs text-slate-900 dark:text-white focus:border-purple-500 focus:outline-none">
                        <option value="admin" {{ $user->role->value === 'admin' ? 'selected' : '' }}>Administrator (Full Governance & Certification Rights)</option>
                        <option value="user" {{ $user->role->value === 'user' ? 'selected' : '' }}>Developer / User (Project Upload & Adoption Rights)</option>
                    </select>
                </div>

                <!-- Security Justification -->
                <div>
                    <label for="reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Audit Justification & Reason <span class="text-rose-400">*</span></label>
                    <textarea id="reason" name="reason" rows="3" required placeholder="State the business rationale or authorization reason for this role change..." class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 p-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:outline-none"></textarea>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">This statement will be permanently stamped into the immutable system audit log.</p>
                </div>

                <!-- Admin Password Sudo Step-Up Check -->
                <div class="rounded-xl border border-purple-500/30 bg-purple-950/20 p-4 space-y-2">
                    <label for="admin_password" class="block text-xs font-bold text-purple-200">Confirm Your Administrator Password <span class="text-rose-400">*</span></label>
                    <input id="admin_password" name="admin_password" type="password" required placeholder="Enter YOUR admin password to authorize" class="block w-full rounded-xl border border-purple-500/40 bg-slate-50 dark:bg-slate-950 py-2.5 px-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-400 focus:outline-none">
                    <p class="text-[10px] text-purple-300">Sudo Mode: Role elevation requires explicit credential verification.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="showPromoteModal = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Cancel</button>
                    <button type="submit" class="rounded-xl bg-purple-600 px-5 py-2 text-xs font-bold text-white hover:bg-purple-500 shadow-lg shadow-purple-950/50 transition">Authorize & Apply Role</button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Projects & Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-4">
            <h2 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Owned Projects ({{ $user->ownedProjects->count() }})</h2>
            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($user->ownedProjects as $p)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-900 dark:text-white font-medium truncate">{{ $p->title }}</span>
                        <x-status-badge :status="$p->status" />
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400">No projects owned by this user.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-6 space-y-4">
            <h2 class="text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 font-semibold">Originally Submitted Projects ({{ $user->uploadedProjects->count() }})</h2>
            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($user->uploadedProjects as $up)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-900 dark:text-white font-medium truncate">{{ $up->title }}</span>
                        <x-status-badge :status="$up->status" />
                    </div>
                @empty
                    <p class="text-xs text-slate-500 dark:text-slate-400">No projects uploaded by this user.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection