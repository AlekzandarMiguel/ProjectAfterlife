@extends('layouts.app', ['title' => 'Settings — Project Afterlife', 'header' => 'Settings & Profile'])

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Profile Info Form -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Profile Information</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage your public developer presence and custom profile photo.</p>
            </div>
            <a href="{{ route('user.profile.show') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                View Public Profile &rarr;
            </a>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
            avatarPreview: null,
            removeAvatar: false,
            handleFile(e) {
                const file = e.target.files[0];
                if (file) {
                    this.removeAvatar = false;
                    const reader = new FileReader();
                    reader.onload = (e) => { this.avatarPreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            }
        }">
            @csrf
            @method('PUT')

            <!-- Custom Avatar Upload Section -->
            <div class="p-5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="relative shrink-0">
                    <template x-if="avatarPreview">
                        <img :src="avatarPreview" class="h-20 w-20 rounded-full object-cover ring-2 ring-emerald-500 shadow-sm" alt="Preview">
                    </template>
                    <template x-if="!avatarPreview">
                        <x-user-avatar :user="$user" size="w-20 h-20" textSize="text-2xl" class="ring-2 ring-emerald-500/40 shadow-sm" />
                    </template>
                </div>

                <div class="space-y-2 flex-1">
                    <label class="block text-xs font-bold text-slate-900 dark:text-white">Profile Photo</label>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Upload a custom profile image. Supported formats: JPG, PNG, WEBP, or GIF (up to 2MB).</p>
                    
                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition shadow-xs">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span>Choose Photo</span>
                            <input type="file" name="avatar" accept="image/*" class="hidden" @change="handleFile($event)">
                        </label>

                        @if($user->avatar)
                            <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 cursor-pointer font-medium">
                                <input type="checkbox" name="remove_avatar" value="1" x-model="removeAvatar" class="rounded border-slate-300 dark:border-slate-700 text-rose-600 focus:ring-rose-500">
                                <span>Remove current photo</span>
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Username *</label>
                    <input type="text" name="username" required value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Email Address *</label>
                <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Bio</label>
                <textarea name="bio" rows="3" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="Tell the community about your background and engineering focus...">{{ old('bio', $user->profile?->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Location</label>
                    <input type="text" name="location" value="{{ old('location', $user->profile?->location) }}" placeholder="e.g. San Francisco, CA" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">GitHub Profile URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $user->github_url) }}" placeholder="https://github.com/..." class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Skills (Comma-separated)</label>
                <input type="text" name="skills_input" value="{{ old('skills_input', is_array($user->profile?->skills) ? implode(', ', $user->profile->skills) : '') }}" placeholder="PHP, Laravel, MySQL, Docker, Rust" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Password Change Form -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-8 space-y-6">
        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Security & Password</h2>
        <form action="{{ route('user.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Current Password *</label>
                <input type="password" name="current_password" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">New Password *</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="rounded-xl bg-slate-800 dark:bg-slate-700 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-700 dark:hover:bg-slate-600 transition shadow-sm cursor-pointer">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
