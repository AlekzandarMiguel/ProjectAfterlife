<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col border-r border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-950 transition-colors">
    <div class="flex h-16 shrink-0 items-center px-6 border-b border-slate-200 dark:border-slate-800/80">
        <a href="{{ route('user.dashboard') }}">
            <x-application-logo size="sm" />
        </a>
    </div>

    <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-6">
        <nav class="space-y-6">
            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">Main</div>
                <div class="space-y-1">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.dashboard') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('explore.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('explore.*') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span>Explore Repository</span>
                    </a>
                    <a href="{{ route('user.projects.create') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.projects.create') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Upload Project</span>
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">My Projects</div>
                <div class="space-y-1">
                    <a href="{{ route('user.projects.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.projects.index') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span>My Projects</span>
                    </a>
                    <a href="{{ route('user.adoptions.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.adoptions.*') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Adoption Requests</span>
                    </a>
                    <a href="{{ route('user.recovery.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.recovery.*') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <span>Recovery Workspace</span>
                    </a>
                    <a href="{{ route('user.bookmarks.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.bookmarks.*') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <span>Watchlist</span>
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold px-2 mb-2">Account</div>
                <div class="space-y-1">
                    <a href="{{ route('user.notifications.index') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.notifications.*') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0 group-hover:animate-bell transition-transform origin-top" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span>Notifications</span>
                        </div>
                    </a>
                    <a href="{{ route('user.profile.show') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.profile.show') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs transition-all duration-150 {{ request()->routeIs('user.profile.edit') ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border-l-4 border-l-emerald-600 dark:border-l-emerald-400 border border-emerald-200 dark:border-emerald-800/60 font-bold shadow-xs dark:shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 hover:translate-x-1 dark:hover:bg-slate-900/60 dark:hover:text-emerald-300 dark:hover:border-emerald-500/20 border border-transparent font-medium' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Session Footer -->
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
