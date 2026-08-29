@extends('layouts.admin', ['title' => 'All Projects — Project Afterlife', 'header' => 'All Platform Projects'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Platform Software Inventory</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage and inspect all projects across any lifecycle status.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.projects.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-white/60 dark:bg-slate-900/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white">
        <select name="status" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white">
            <option value="">All Statuses</option>
            @foreach($statuses as $st)
                <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
            @endforeach
        </select>
        <select name="category" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition">Filter</button>
    </form>

    <!-- Table -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-950 font-mono uppercase text-[10px] text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-3">Project</th>
                    <th class="px-6 py-3">Owner</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Featured</th>
                    <th class="px-6 py-3">Activity</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($projects as $p)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/60 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $p->title }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $p->category->name ?? 'General' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-800 dark:text-slate-200">
                            {{ $p->owner->name }}
                        </td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$p->status" />
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.projects.toggle-featured', $p) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-[11px] font-mono {{ $p->is_featured ? 'text-amber-400 font-bold' : 'text-slate-500' }}">
                                    {{ $p->is_featured ? '★ Featured' : '☆ Standard' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono text-[11px]">
                            {{ $p->last_activity_at?->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('explore.show', $p) }}" target="_blank" class="text-purple-400 hover:underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">{{ $projects->links() }}</div>
</div>
@endsection