<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Developer Account — Project Afterlife</title>
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

            <div class="flex items-center justify-between relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back to Home Portal</span>
                </a>
                <span class="text-[11px] font-mono text-emerald-300 bg-emerald-950/80 border border-emerald-500/30 px-3 py-1 rounded-full">Open Registration</span>
            </div>

            <div class="my-auto text-center py-12 relative z-10">
                <div class="mx-auto flex items-center justify-center text-white filter drop-shadow-[0_10px_20px_rgba(16,185,129,0.3)]" style="width: 104px; height: 104px;">
                    <x-logo-icon size="104" color="text-white" />
                </div>

                <h1 class="mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-white">
                    Project Afterlife
                </h1>

                <p class="mt-3 text-sm sm:text-base text-emerald-100 max-w-md mx-auto font-normal leading-relaxed">
                    Join the open-source recovery platform to upload, adopt, or resurrect abandoned projects.
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Upload</span>
                    <span class="text-[10px] text-emerald-200/70">Submit Repos</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Adopt</span>
                    <span class="text-[10px] text-emerald-200/70">Claim Software</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Resurrect</span>
                    <span class="text-[10px] text-emerald-200/70">Certified Builds</span>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/90 rounded-3xl shadow-2xl shadow-emerald-950/30 p-8 sm:p-10 border border-slate-800 backdrop-blur-sm">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Create Account</h2>
                    <p class="mt-1.5 text-xs sm:text-sm text-slate-400">Sign up to start adopting and managing software</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST" class="mt-6 space-y-4" novalidate>
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Full Name <span class="text-rose-400">*</span></label>
                        <div class="relative rounded-xl border @error('name') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="e.g. Jane Doe" class="block w-full rounded-xl bg-transparent py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:outline-none">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-300 mb-1">Username <span class="text-rose-400">*</span></label>
                        <div class="relative rounded-xl border @error('username') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <input id="username" name="username" type="text" required value="{{ old('username') }}" placeholder="e.g. janedev" class="block w-full rounded-xl bg-transparent py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:outline-none font-mono">
                        </div>
                        @error('username')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Email Address <span class="text-rose-400">*</span></label>
                        <div class="relative rounded-xl border @error('email') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="jane@domain.com" class="block w-full rounded-xl bg-transparent py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:outline-none">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Passwords -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-300 mb-1">Password <span class="text-rose-400">*</span></label>
                            <input id="password" name="password" type="password" required placeholder="Min. 8 chars" class="block w-full rounded-xl border @error('password') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1">Confirm <span class="text-rose-400">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Repeat password" class="block w-full rounded-xl border @error('password') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none">
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-medium">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    <!-- Terms Checkbox -->
                    <div class="pt-1">
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required value="1" {{ old('terms') ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-slate-800 bg-slate-950 text-emerald-600 focus:ring-emerald-500">
                            <label for="terms" class="ml-2 block text-xs text-slate-400">
                                I agree to the <a href="{{ route('about') }}" class="text-emerald-400 font-semibold hover:underline">platform terms and software transfer ethics</a>. <span class="text-rose-400">*</span>
                            </label>
                        </div>
                        @error('terms')
                            <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-950/50 hover:bg-emerald-500 active:scale-[0.99] transition">
                            <span>Register Account</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
