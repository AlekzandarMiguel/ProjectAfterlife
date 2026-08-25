<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify 6-Digit Code — Project Afterlife</title>
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
                <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Change Email Address</span>
                </a>
                <span class="text-[11px] font-mono text-emerald-300 bg-emerald-950/80 border border-emerald-500/30 px-3 py-1 rounded-full">Step 2 of 3</span>
            </div>

            <div class="my-auto text-center py-12 relative z-10">
                <div class="mx-auto flex items-center justify-center text-white filter drop-shadow-[0_10px_20px_rgba(16,185,129,0.3)]" style="width: 104px; height: 104px;">
                    <x-logo-icon size="104" color="text-white" />
                </div>

                <h1 class="mt-6 text-3xl sm:text-5xl font-extrabold tracking-tight text-white">
                    Enter 6-Digit Code
                </h1>

                <p class="mt-3 text-sm sm:text-base text-emerald-100 max-w-md mx-auto font-normal leading-relaxed">
                    Check your email inbox for the 6-digit authentication code sent to <strong class="text-white font-semibold">{{ $email }}</strong>.
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
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">15 Minutes</span>
                    <span class="text-[10px] text-emerald-200/70">Time Limit</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">Cryptographic</span>
                    <span class="text-[10px] text-emerald-200/70">Hashed OTP</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-slate-900/60 border border-emerald-500/30 flex items-center justify-center text-emerald-300 mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                    </div>
                    <span class="text-xs font-semibold text-white">One-Time Use</span>
                    <span class="text-[10px] text-emerald-200/70">Auto Expire</span>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-slate-950">
            <div class="w-full max-w-md bg-slate-900/90 rounded-3xl shadow-2xl shadow-emerald-950/30 p-8 sm:p-10 border border-slate-800 backdrop-blur-sm">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Enter Verification Code</h2>
                    <p class="mt-1.5 text-xs sm:text-sm text-slate-400">Type the 6-digit numeric code sent to your email.</p>
                </div>

                @if(session('status'))
                    <div class="mt-4 rounded-xl bg-emerald-950/60 border border-emerald-500/40 p-4 text-xs text-emerald-200 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-4 rounded-xl bg-rose-950/60 border border-rose-500/40 p-4 text-xs text-rose-200">
                        @foreach($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.verify.code') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label for="code" class="block text-xs font-semibold text-slate-300 mb-2 text-center">6-Digit Code</label>
                        <div class="flex justify-center">
                            <input 
                                id="code" 
                                name="code" 
                                type="text" 
                                inputmode="numeric" 
                                pattern="[0-9]*" 
                                maxlength="6" 
                                required 
                                autofocus 
                                placeholder="------" 
                                class="w-64 tracking-[1em] text-center font-mono text-2xl font-bold bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 text-emerald-400 rounded-2xl py-3 px-4 transition placeholder-slate-600 outline-none"
                            />
                        </div>
                        <p class="text-[11px] text-slate-500 text-center mt-2">Format: 6 digits (e.g. 123456)</p>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition duration-150 shadow-lg shadow-emerald-950/50 flex items-center justify-center gap-2">
                        <span>Verify Code & Continue</span>
                        <span>&rarr;</span>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Didn't receive the code?</span>
                    <form action="{{ route('password.email') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="font-semibold text-emerald-400 hover:text-emerald-300 transition">
                            Resend Code &rarr;
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
