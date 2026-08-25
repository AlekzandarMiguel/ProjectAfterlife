@extends('layouts.admin', ['title' => 'Provision New User / Admin — Project Afterlife', 'header' => 'Create Account'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-white tracking-tight">Provision New Account</h2>
            <p class="text-xs text-slate-400 mt-0.5">Directly create and activate a Developer or Administrator account.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-slate-400 hover:text-white transition flex items-center gap-1">
            &larr; Back to Directory
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-rose-800/60 bg-rose-950/30 p-4 text-xs text-rose-300">
            <p class="font-bold mb-1">Please correct the errors below:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur p-6 sm:p-8 space-y-6 shadow-xl">
            <h3 class="text-xs uppercase font-mono tracking-wider text-purple-400 font-semibold border-b border-slate-800 pb-3">
                Account Identity & Credentials
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Full Name <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Alex Mercer" class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" />
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Username <span class="text-rose-400">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="e.g. alexmercer" class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" />
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Email Address <span class="text-rose-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. alex@afterlife.dev" class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Password <span class="text-rose-400">*</span></label>
                    <input type="password" name="password" required placeholder="Min 8 chars, letters & numbers" class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" />
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Confirm Password <span class="text-rose-400">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat password" class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" />
                </div>
            </div>

            <h3 class="text-xs uppercase font-mono tracking-wider text-purple-400 font-semibold border-b border-slate-800 pb-3 pt-2">
                Permissions & Role Assignment
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Role -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Assigned System Role <span class="text-rose-400">*</span></label>
                    <select name="role" required class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Developer / Standard User</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>🛡️ Administrator (Full System Control)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Administrators have full moderation, verification, and audit access.</p>
                </div>

                <!-- Initial Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Initial Account Status <span class="text-rose-400">*</span></label>
                    <select name="status" required class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>🟢 Active (Immediate Login Access)</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>⏳ Pending Verification</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>🔴 Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Optional Bio -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Developer Bio / Admin Note (Optional)</label>
                <textarea name="bio" rows="3" placeholder="Brief description of the account or administrative duties..." class="w-full rounded-xl bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-xs text-white placeholder-slate-600 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">{{ old('bio') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-950/50 transition duration-150 flex items-center gap-2">
                <span>✓ Create & Provision Account</span>
            </button>
        </div>
    </form>
</div>
@endsection
