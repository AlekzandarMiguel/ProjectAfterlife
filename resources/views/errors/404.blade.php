@extends('layouts.guest', ['title' => '404 — Project Not Found'])

@section('content')
<div class="flex min-h-[calc(100vh-12rem)] items-center justify-center text-center p-4">
    <div class="space-y-4 max-w-md">
        <div class="text-4xl font-mono font-bold text-emerald-400">404</div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Resource Not Found</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">The requested software project or page does not exist in the Afterlife registry.</p>
        <div class="pt-2">
            <a href="{{ route('explore.index') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition inline-block">Browse Repository</a>
        </div>
    </div>
</div>
@endsection