<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in to System — Project Afterlife</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-slate-950 bg-slate-950">
    <div class="flex-1 flex flex-col lg:flex-row min-h-screen">
        
        <!-- LEFT VIBRANT HERO PANEL -->
        <div class="lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 lg:p-16 bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-950 text-white relative overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-800/80">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back to Home Portal</span>
                </a>
                <span class="text-[11px] font-mono text-emerald-300 bg-emerald-950/80 border border-emerald-500/30 px-3 py-1 rounded-full">v1.0 Public Release</span>
            </div>

            <div class="my-auto text-center py-12 relative z-10">
                <div class="mx-auto flex items-center justify-center text-white filter drop-shadow-[0_10px_20px_rgba(16,185,129,0.3)]" style="width: 104px; height: 104px;">
                    <x-logo-icon size="104" color="text-white" />
                </div>

                <h1 class="mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-white">
                    Project Afterlife
                </h1>

                <p class="mt-3 text-sm sm:text-base text-emerald-100 max-w-md mx-auto font-normal leading-relaxed">
                    A Web-Based Abandoned Software Project Recovery and Ownership Transfer System
                </p>

                <div class="mt-8 flex items-center justify-center gap-3">
                    <div class="h-[1px] w-16 bg-emerald-400/30"></div>
                    <div class="h-2 w-2 rounded-full bg-emerald-400"></div>
                    <div class="h-[1px] w-16 bg-emerald-400/30"></div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-6 text-center relative z-10 border-t border-emerald-500/20">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Code Recovery</span>
                    <span class="text-[10px] text-emerald-200/70">Preserve Software</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Legal Transfer</span>
                    <span class="text-[10px] text-emerald-200/70">Atomic Ownership</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Resurrection</span>
                    <span class="text-[10px] text-emerald-200/70">Certified Builds</span>
                </div>
            </div>
        </div>

        <!-- RIGHT ELEVATED CARD PANEL -->
        <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/90 rounded-3xl shadow-2xl shadow-emerald-950/30 p-8 sm:p-10 border border-slate-800 backdrop-blur-sm">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Sign in to System</h2>
                    <p class="mt-1.5 text-xs sm:text-sm text-slate-400">Enter your credentials to continue</p>
                </div>

                @if(session('success'))
                    <div class="mt-4 rounded-xl bg-emerald-950/60 border border-emerald-500/40 p-3.5 text-xs text-emerald-200 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-4 rounded-xl bg-rose-950/60 border border-rose-500/40 p-3.5 text-xs text-rose-200 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="mt-6 space-y-4" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email address <span class="text-rose-400">*</span></label>
                        <div class="relative rounded-xl border @error('email') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="your@email.com" class="block w-full rounded-xl bg-transparent py-2.5 pl-10 pr-3 text-sm text-white placeholder-slate-400 focus:outline-none">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-300">Password <span class="text-rose-400">*</span></label>
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 hover:underline">Forgot Password?</a>
                        </div>
                        <div class="relative rounded-xl border @error('password') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••" class="block w-full rounded-xl bg-transparent py-2.5 pl-10 pr-3 text-sm text-white placeholder-slate-400 focus:outline-none">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-950/50 hover:bg-emerald-500 active:scale-[0.99] transition">
                            <span>Sign In</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase font-mono tracking-wider">
                        <span class="bg-slate-900 px-3 text-slate-400 font-semibold">Or Quick Demo Sign In</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="document.getElementById('email').value='admin@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2 rounded-xl border border-purple-500/30 bg-purple-950/30 px-3 py-2 text-left hover:bg-purple-900/40 hover:border-purple-400 transition">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-600/30 text-purple-300 font-bold text-xs">A</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-purple-200 truncate">Alexander (Admin)</div>
                            <div class="text-[10px] text-purple-400 font-mono truncate">admin@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='elena@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-950/30 px-3 py-2 text-left hover:bg-emerald-900/40 hover:border-emerald-400 transition">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-600/30 text-emerald-300 font-bold text-xs">E</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-emerald-200 truncate">Elena (Creator)</div>
                            <div class="text-[10px] text-emerald-400 font-mono truncate">elena@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='devon@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2 rounded-xl border border-slate-800 bg-slate-950/60 px-3 py-2 text-left hover:bg-slate-800 hover:border-slate-700 transition">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-slate-300 font-bold text-xs">D</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-slate-200 truncate">Devon (Resurrector)</div>
                            <div class="text-[10px] text-slate-400 font-mono truncate">devon@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='marcus@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2 rounded-xl border border-slate-800 bg-slate-950/60 px-3 py-2 text-left hover:bg-slate-800 hover:border-slate-700 transition">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-slate-300 font-bold text-xs">M</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-slate-200 truncate">Marcus (Adopter)</div>
                            <div class="text-[10px] text-slate-400 font-mono truncate">marcus@afterlife.dev</div>
                        </div>
                    </button>
                </div>

                <div class="mt-6 text-center space-y-2">
                    <p class="text-xs text-slate-400">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline">Register now</a>
                    </p>
                    <a href="{{ route('home') }}" class="block text-[11px] text-slate-400 hover:text-slate-300 transition">
                        Public Portal &bull; Explore Repository
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
