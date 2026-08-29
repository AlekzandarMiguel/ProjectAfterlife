<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Console — Project Afterlife' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 font-sans selection:bg-purple-500 selection:text-slate-950" x-data="{ sidebarOpen: false }">
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
             class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm lg:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Main Wrapper -->
        <div class="flex flex-1 flex-col lg:pl-64">
            <!-- Admin Topbar -->
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" class="text-slate-400 hover:text-white lg:hidden p-1.5 rounded-lg border border-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded bg-purple-500/10 px-2 py-0.5 text-xs font-semibold text-purple-400 ring-1 ring-inset ring-purple-500/30">
                            ADMINISTRATOR
                        </span>
                        <h1 class="text-sm font-semibold text-slate-200 hidden sm:block">
                            {{ $header ?? 'System Control Center' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition">
                        <span>View Public Portal</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>

                    <!-- Admin User Pill -->
                    <div class="flex items-center gap-3 border-l border-slate-800 pl-4">
                        <img class="h-7 w-7 rounded-full bg-purple-900 ring-1 ring-purple-500/50" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                        <div class="hidden sm:flex flex-col">
                            <span class="text-xs font-semibold text-slate-200">{{ auth()->user()->name }}</span>
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
