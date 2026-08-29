@extends('layouts.auth')

@section('title', 'Registration Received — Awaiting Verification')

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-2xl p-8 shadow-2xl relative overflow-hidden text-center">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Pending Clock / Verification Icon -->
        <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 text-amber-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-semibold bg-amber-950/50 text-amber-300 border border-amber-800/40 mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
            Awaiting Verification
        </span>

        <h1 class="text-2xl font-bold text-white tracking-tight mb-2">Registration Received!</h1>
        <p class="text-sm text-slate-400 mb-6 leading-relaxed">
            Thank you for signing up for <strong class="text-white">Project Afterlife</strong>. To protect the integrity and security of abandoned software repositories, new accounts are manually verified by an administrator before activation.
        </p>

        <!-- Verification Steps Box -->
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-4 text-left mb-6 space-y-3">
            <div class="flex items-start gap-3">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold shrink-0 mt-0.5">✓</span>
                <div class="text-xs text-slate-300">
                    <p class="font-medium text-white">Account Created</p>
                    <p class="text-slate-400 text-[11px]">Developer profile and credentials stored safely.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold shrink-0 mt-0.5 animate-pulse">2</span>
                <div class="text-xs text-slate-300">
                    <p class="font-medium text-amber-300">Administrator Review</p>
                    <p class="text-slate-400 text-[11px]">Our team verifies credentials to prevent automated spam and malicious uploads.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-800 text-slate-500 text-xs font-bold shrink-0 mt-0.5">3</span>
                <div class="text-xs text-slate-400">
                    <p class="font-medium text-slate-300">Access Granted</p>
                    <p class="text-slate-400 text-[11px]">You will be able to log in, adopt abandoned code, and submit projects.</p>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm rounded-xl shadow-lg shadow-emerald-900/30 transition duration-150">
                Go to Sign In
            </a>
            <a href="{{ route('explore.index') }}" class="w-full flex items-center justify-center py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-sm rounded-xl transition duration-150">
                Browse Public Repository
            </a>
        </div>
    </div>
</div>
@endsection
