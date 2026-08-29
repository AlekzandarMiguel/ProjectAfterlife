@extends('layouts.guest', ['title' => '403 — Unauthorized Access'])

@section('content')
<div class="flex min-h-[calc(100vh-12rem)] items-center justify-center text-center p-4">
    <div class="space-y-4 max-w-md">
        <div class="text-4xl font-mono font-bold text-rose-500">403</div>
        <h1 class="text-xl font-bold text-white">Access Forbidden</h1>
        <p class="text-xs text-slate-400">You do not have the required permissions or system role to access this resource.</p>
        <div class="pt-2">
            <a href="{{ route('home') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition inline-block">Return to Safety</a>
        </div>
    </div>
</div>
@endsection
