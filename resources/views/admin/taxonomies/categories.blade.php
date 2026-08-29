@extends('layouts.admin', ['title' => 'Categories — Project Afterlife', 'header' => 'Category Taxonomies'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ openAdd: false }">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Categories</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage software classification categories.</p>
        </div>

        <button type="button" @click="openAdd = true" class="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition">
            + New Category
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($categories as $cat)
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 p-5 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">{{ $cat->name }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $cat->description }}</p>
                    <span class="text-[10px] text-purple-400 font-mono mt-2 block">{{ $cat->projects_count }} projects</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div x-show="openAdd" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openAdd = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Create Category</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Embedded Systems" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Description</label>
                    <textarea name="description" rows="2" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openAdd = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection