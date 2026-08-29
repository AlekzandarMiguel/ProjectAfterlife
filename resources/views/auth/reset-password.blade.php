<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set New Password — Project Afterlife</title>
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
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back to Sign In</span>
                </a>
                <span class="text-[11px] font-mono text-emerald-300 bg-emerald-950/80 border border-emerald-500/30 px-3 py-1 rounded-full">Secure Reset</span>
            </div>

            <div class="my-auto text-center py-12 relative z-10">
                <div class="mx-auto flex items-center justify-center text-white filter drop-shadow-[0_10px_20px_rgba(16,185,129,0.3)]" style="width: 104px; height: 104px;">
                    <x-logo-icon size="104" color="text-white" />
                </div>

                <h1 class="mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-white">
                    Set New Password
                </h1>

                <p class="mt-3 text-sm sm:text-base text-emerald-100 max-w-md mx-auto font-normal leading-relaxed">
                    Create a strong, new password with letters and numbers to restore account protection.
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Min 8 Chars</span>
                    <span class="text-[10px] text-emerald-200/70">Length Check</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Mixed Case</span>
                    <span class="text-[10px] text-emerald-200/70">Letters & Numbers</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Confirmation</span>
                    <span class="text-[10px] text-emerald-200/70">Match Guard</span>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/90 rounded-3xl shadow-2xl shadow-emerald-950/30 p-8 sm:p-10 border border-slate-800 backdrop-blur-sm">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Create New Password</h2>
                    <p class="mt-1.5 text-xs sm:text-sm text-slate-400">Enter and confirm your new account password.</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="mt-6 space-y-4" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Account Email <span class="text-rose-400">*</span></label>
                        <div class="relative rounded-xl border @error('email') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition">
                            <input id="email" name="email" type="email" required value="{{ old('email', $email) }}" class="block w-full rounded-xl bg-transparent py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:outline-none">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">New Password <span class="text-rose-400">*</span></label>
                        <input id="password" name="password" type="password" required placeholder="Min. 8 chars, letters & numbers" class="block w-full rounded-xl border @error('password') border-rose-500 ring-1 ring-rose-500/50 @else border-slate-800 @enderror bg-slate-950/80 py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none">
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1.5">Confirm New Password <span class="text-rose-400">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Repeat new password" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 py-2.5 px-3.5 text-sm text-white placeholder-slate-400 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-950/50 hover:bg-emerald-500 active:scale-[0.99] transition">
                            <span>Update Password & Sign In</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
