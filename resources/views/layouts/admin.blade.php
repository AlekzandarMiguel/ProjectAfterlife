<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Console — Project Afterlife' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans selection:bg-purple-500 selection:text-white dark:selection:text-slate-950" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Admin Sidebar -->
        @include('components.sidebar-admin')

        <!-- Mobile Drawer Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm lg:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Main Wrapper -->
        <div class="flex flex-1 flex-col lg:pl-64">
            <!-- Admin Topbar -->
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md px-4 sm:px-6 lg:px-8 transition-colors">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:text-white lg:hidden p-1.5 rounded-lg border border-slate-200 dark:border-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded bg-purple-500/10 px-2 py-0.5 text-xs font-semibold text-purple-400 border border-purple-300 dark:border-purple-500/30">
                            ADMINISTRATOR
                        </span>
                        <h1 class="text-sm font-semibold text-slate-800 dark:text-slate-200 hidden sm:block">
                            {{ $header ?? 'System Control Center' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:text-white transition">
                        <span>View Public Portal</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>

                    @php
                        $adminUnreadNotifs = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('notifiable_type', \App\Models\User::class)
                            ->where('notifiable_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp

                    <x-theme-toggle />

                    <!-- Notification Bell -->
                    <a href="{{ route('admin.notifications.index') }}" class="relative p-2 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900 border border-transparent hover:border-slate-200 dark:border-slate-800 transition" title="System Notifications">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($adminUnreadNotifs > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-purple-500"></span>
                            </span>
                        @endif
                    </a>

                    <!-- Admin User Pill -->
                    <div class="flex items-center gap-3 border-l border-slate-200 dark:border-slate-800 pl-4">
                        <img class="h-7 w-7 rounded-full bg-purple-900 /50" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                        <div class="hidden sm:flex flex-col">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-purple-400 font-mono">Platform Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-4 sm:px-6 lg:px-8 pt-4">
                @if(session('success'))
                    <div class="rounded-lg bg-emerald-950/60 border border-emerald-500/40 p-4 text-sm text-emerald-200 flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-lg bg-rose-950/60 border border-rose-500/40 p-4 text-sm text-rose-200 flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>