@extends('layouts.app', ['title' => 'Settings — Project Afterlife', 'header' => 'Settings & Profile'])

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Profile Info Form -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-8 space-y-6">
        <h2 class="text-base font-bold text-white tracking-tight">Profile Information</h2>
        <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300">Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300">Username *</label>
                    <input type="text" name="username" required value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300">Email Address *</label>
                <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300">Bio</label>
                <textarea name="bio" rows="3" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('bio', $user->profile?->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300">Location</label>
                    <input type="text" name="location" value="{{ old('location', $user->profile?->location) }}" placeholder="e.g. San Francisco, CA" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300">GitHub Profile URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $user->github_url) }}" placeholder="https://github.com/..." class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-300">Skills (Comma-separated)</label>
                <input type="text" name="skills_input" value="{{ old('skills_input', is_array($user->profile?->skills) ? implode(', ', $user->profile->skills) : '') }}" placeholder="PHP, Laravel, MySQL, Docker, Rust" class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Password Change Form -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-8 space-y-6">
        <h2 class="text-base font-bold text-white tracking-tight">Security & Password</h2>
        <form action="{{ route('user.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-slate-300">Current Password</label>
                <input type="password" name="current_password" required class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300">New Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
