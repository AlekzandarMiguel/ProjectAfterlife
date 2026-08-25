<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Project Afterlife — Software Recovery & Ownership Transfer' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-slate-950 font-sans selection:bg-emerald-500 selection:text-slate-950">
    <!-- Navigation -->
    <header class="sticky top-0 z-40 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}">
                    <x-application-logo size="md" />
                </a>

                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                    <a href="{{ route('explore.index') }}" class="hover:text-emerald-400 transition {{ request()->routeIs('explore.*') ? 'text-emerald-400 font-semibold' : '' }}">Explore Projects</a>
                    <a href="{{ route('resurrected.index') }}" class="hover:text-emerald-400 transition {{ request()->routeIs('resurrected.*') ? 'text-emerald-400 font-semibold' : '' }}">Resurrected Gallery</a>
                    <a href="{{ route('about') }}" class="hover:text-emerald-400 transition {{ request()->routeIs('about') ? 'text-emerald-400 font-semibold' : '' }}">About</a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-purple-500 transition">
                            <span>Admin Console</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition">
                            <span>My Workspace</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-300 hover:text-white transition px-3 py-2">Sign in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-slate-800 border border-slate-700 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-700 transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success') || session('error') || session('info') || $errors->any())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="rounded-lg bg-emerald-950/60 border border-emerald-500/40 p-4 text-sm text-emerald-200 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-lg bg-rose-950/60 border border-rose-500/40 p-4 text-sm text-rose-200 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="rounded-lg bg-rose-950/60 border border-rose-500/40 p-4 text-sm text-rose-200">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-950 py-12 text-slate-400">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <a href="{{ route('home') }}">
                <x-application-logo size="sm" />
            </a>
            <div class="flex items-center gap-6 text-xs text-slate-400">
                <a href="{{ route('explore.index') }}" class="hover:text-white transition">Repository</a>
                <a href="{{ route('resurrected.index') }}" class="hover:text-white transition">Resurrections</a>
                <a href="{{ route('about') }}" class="hover:text-white transition">Philosophy</a>
                <span>Strict Relational Transfer • No AI</span>
            </div>
        </div>
    </footer>
</body>
</html>
