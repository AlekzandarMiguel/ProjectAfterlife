<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in to System — Project Afterlife</title>
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
<body class="min-h-full flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-slate-950 bg-slate-50 dark:bg-slate-950">
    <div class="flex-1 flex flex-col lg:flex-row min-h-screen">
        
        <!-- LEFT VIBRANT HERO PANEL (Always crisp white text on deep emerald gradient) -->
        <div class="lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 lg:p-16 bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-950 text-white relative overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-slate-800">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-200 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back to Home Portal</span>
                </a>
                <span class="text-[11px] font-mono text-emerald-300 bg-emerald-950/80 border border-emerald-500/30 px-3 py-1 rounded-full">v1.0 Public Release</span>
            </div>

            <div class="my-auto text-center py-12 relative z-10">
                <div class="mx-auto flex items-center justify-center text-white filter drop-shadow-[0_10px_20px_rgba(16,185,129,0.3)]" style="width: 104px; height: 104px;">
                    <x-logo-icon size="104" :forceWhite="true" />
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
                    <div class="w-12 h-12 rounded-full bg-emerald-950/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Code Recovery</span>
                    <span class="text-[10px] text-emerald-200/70">Preserve Software</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-950/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Legal Transfer</span>
                    <span class="text-[10px] text-emerald-200/70">Atomic Ownership</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-950/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
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
        <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-slate-50 dark:bg-slate-950 transition-colors">
            <div class="w-full max-w-md bg-white dark:bg-slate-900/90 rounded-3xl shadow-xl p-8 sm:p-10 border border-slate-200 dark:border-slate-800 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Sign in to System</h2>
                        <p class="mt-1.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">Enter your credentials to continue</p>
                    </div>
                    <x-theme-toggle />
                </div>

                @if(session('success'))
                    <div class="mt-4 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-500/40 p-3.5 text-xs text-emerald-800 dark:text-emerald-200 flex items-center gap-2">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-4 rounded-xl bg-rose-100 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-500/40 p-3.5 text-xs text-rose-800 dark:text-rose-200 space-y-1">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-800 dark:text-slate-200">Email address *</label>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email') }}" 
                                   placeholder="your@email.com"
                                   class="block w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 pl-10 pr-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition shadow-xs">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold text-slate-800 dark:text-slate-200">Password *</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Forgot Password?</a>
                        </div>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required 
                                   placeholder="••••••••"
                                   class="block w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 pl-10 pr-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition shadow-xs">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm">
                            <span>Sign In</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                        </div>
                        <div class="relative flex justify-center text-[10px] uppercase font-mono tracking-wider">
                            <span class="bg-white dark:bg-slate-900 px-3 text-slate-500 dark:text-slate-400 font-semibold">Or continue with</span>
                        </div>
                    </div>

                    <!-- Google Sign In Button Under Login -->
                    <div>
                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-900 transition shadow-xs">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Sign in with Google</span>
                        </a>
                    </div>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase font-mono tracking-wider">
                        <span class="bg-white dark:bg-slate-900 px-3 text-slate-500 dark:text-slate-400 font-bold">Or Quick Demo Sign In</span>
                    </div>
                </div>

                <!-- High-contrast Quick Demo Sign-in Cards -->
                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" onclick="document.getElementById('email').value='admin@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2.5 rounded-xl border border-purple-200 dark:border-purple-800/60 bg-purple-50/70 dark:bg-purple-950/40 p-2.5 text-left hover:bg-purple-100 dark:hover:bg-purple-900/50 hover:border-purple-300 dark:hover:border-purple-500 transition shadow-xs cursor-pointer">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-purple-600 text-white font-bold text-xs shadow-xs">A</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-purple-900 dark:text-purple-200 truncate">Alexander (Admin)</div>
                            <div class="text-[10px] text-purple-700 dark:text-purple-400 font-mono truncate">admin@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='elena@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/70 dark:bg-emerald-950/40 p-2.5 text-left hover:bg-emerald-100 dark:hover:bg-emerald-900/50 hover:border-emerald-300 dark:hover:border-emerald-500 transition shadow-xs cursor-pointer">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-xs">E</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-emerald-900 dark:text-emerald-200 truncate">Elena (Creator)</div>
                            <div class="text-[10px] text-emerald-700 dark:text-emerald-400 font-mono truncate">elena@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='devon@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2.5 rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 p-2.5 text-left hover:bg-slate-50 dark:hover:bg-slate-900 hover:border-slate-400 dark:hover:border-slate-700 transition shadow-xs cursor-pointer">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold text-xs shadow-xs">D</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-200 truncate">Devon (Resurrector)</div>
                            <div class="text-[10px] text-slate-600 dark:text-slate-400 font-mono truncate">devon@afterlife.dev</div>
                        </div>
                    </button>

                    <button type="button" onclick="document.getElementById('email').value='marcus@afterlife.dev';document.getElementById('password').value='password';" class="flex items-center gap-2.5 rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 p-2.5 text-left hover:bg-slate-50 dark:hover:bg-slate-900 hover:border-slate-400 dark:hover:border-slate-700 transition shadow-xs cursor-pointer">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold text-xs shadow-xs">M</div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-200 truncate">Marcus (Adopter)</div>
                            <div class="text-[10px] text-slate-600 dark:text-slate-400 font-mono truncate">marcus@afterlife.dev</div>
                        </div>
                    </button>
                </div>

                <div class="mt-6 text-center space-y-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Register now</a>
                    </p>
                    <a href="{{ route('home') }}" class="block text-[11px] text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-300 transition">
                        Public Portal &bull; Explore Repository
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
