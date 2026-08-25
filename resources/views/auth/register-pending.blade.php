<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Received — Project Afterlife</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-slate-950 bg-slate-950 relative overflow-x-hidden">

    <!-- Ambient background glow -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-emerald-500/10 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-0 right-10 w-[600px] h-[400px] bg-amber-500/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- Navigation Header -->
    <header class="w-full border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
            <x-logo-icon size="36" />
            <div class="flex flex-col">
                <span class="text-base font-bold text-white tracking-tight leading-tight">Project Afterlife</span>
                <span class="text-[10px] uppercase font-mono text-emerald-400 font-medium tracking-wider">Software Revival Platform</span>
            </div>
        </a>

        <a href="{{ route('login') }}" class="text-xs font-medium text-slate-300 hover:text-emerald-400 transition flex items-center gap-1.5">
            <span>Sign In</span>
            <span>&rarr;</span>
        </a>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 flex items-center justify-center p-6 py-12">
        <div class="w-full max-w-lg mx-auto">
            <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden text-center">
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Clock / Verification Icon with Brand Logo -->
                <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                        <x-logo-icon size="44" />
                    </div>
                    <span class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center text-xs font-bold ring-4 ring-slate-900 shadow-md">
                        ⏳
                    </span>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-mono font-semibold bg-amber-950/60 text-amber-300 border border-amber-800/50 mb-4">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    Verification In Progress
                </span>

                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mb-2">Registration Received!</h1>
                <p class="text-sm text-slate-400 mb-8 leading-relaxed max-w-md mx-auto">
                    Your developer account has been registered and submitted to the <strong class="text-white">Project Afterlife</strong> administration team for verification and approval.
                </p>

                <!-- Process Milestone Cards -->
                <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 text-left mb-8 space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                            ✓
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-white">1. Account & Profile Created</p>
                            <p class="text-[11px] text-slate-400 leading-relaxed">Your credentials, bio, and initial developer profile are safely stored.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-amber-500/30 animate-pulse">
                            2
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-amber-300">2. Administrator Review</p>
                            <p class="text-[11px] text-slate-400 leading-relaxed">An administrator verifies new signups to protect repositories from unauthorized tampering or spam.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-slate-700">
                            3
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400">3. Full Access Activated</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Once approved, you will be able to log in, adopt abandoned software, and access recovery workspaces.</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('login') }}" class="w-full sm:flex-1 py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition duration-150 shadow-lg shadow-emerald-950/50 flex items-center justify-center gap-2">
                        <span>Go to Sign In</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('explore.index') }}" class="w-full sm:flex-1 py-3 px-5 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700/60 font-semibold text-xs transition duration-150 flex items-center justify-center gap-2">
                        <span>Browse Public Projects</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-800/60 py-4 px-6 text-center text-xs text-slate-500 font-mono">
        Project Afterlife &bull; Dedicated to the Revival and Preservation of Abandoned Software
    </footer>

</body>
</html>
