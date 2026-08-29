<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col border-r border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-950 transition-colors">
    <div class="flex h-16 shrink-0 items-center px-6 border-b border-slate-200 dark:border-slate-800/80">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <x-logo-icon class="h-8 w-8" color="text-purple-600 dark:text-purple-400" />
            <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-300 transition">Project Afterlife</span>
                <span class="text-[10px] text-purple-600 dark:text-purple-400 font-mono">Administration</span>
            </div>
        </a>
    </div>

    <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-6">
        <nav class="space-y-6">
            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">Overview</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Dashboard</span>
                    </a>
                    @php
                        $sidebarUnreadNotifs = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('notifiable_type', \App\Models\User::class)
                            ->where('notifiable_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.notifications.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span>Notifications</span>
                        </div>
                        @if($sidebarUnreadNotifs > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-600 text-white font-mono leading-none shadow-xs dark:shadow-[0_0_8px_rgba(168,85,247,0.6)]">
                                {{ $sidebarUnreadNotifs }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span>Users Directory</span>
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.projects.index') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span>All Projects</span>
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">Workflows & Moderation</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.projects.submissions.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.projects.submissions.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        <span>Project Submissions</span>
                    </a>
                    <a href="{{ route('admin.adoption-requests.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.adoption-requests.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        <span>Adoption Requests</span>
                    </a>
                    <a href="{{ route('admin.ownership-transfers.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.ownership-transfers.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Ownership Transfers</span>
                    </a>
                    <a href="{{ route('admin.recovery.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.recovery.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <span>Recovery Monitoring</span>
                    </a>
                    <a href="{{ route('admin.final-reviews.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.final-reviews.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        <span>Final Reviews</span>
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">System</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.technologies.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.technologies.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        <span>Technologies</span>
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Audit Logs</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-purple-50 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 border-l-4 border-l-purple-600 dark:border-l-purple-400 border border-purple-200 dark:border-purple-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-purple-300 dark:hover:border-purple-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Platform Settings</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Admin Session Footer -->
        <div class="border-t border-slate-200 dark:border-slate-800/80 pt-4 mt-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-xs font-medium text-slate-500 dark:text-slate-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 hover:text-rose-600 dark:hover:text-rose-400 hover:translate-x-1 transition-all duration-150">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>
