@extends('layouts.admin', ['title' => 'Technologies — Project Afterlife', 'header' => 'Technology Stack Taxonomies'])

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ openAdd: false }">
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Technologies & Frameworks</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Normalized database of languages, frameworks, databases, and libraries.</p>
        </div>

        <button type="button" @click="openAdd = true" class="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-500 transition">
            + New Technology
        </button>
    </div>

    <!-- Tech Table -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-950 font-mono uppercase text-[10px] text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Linked Projects</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($technologies as $tech)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/60 transition">
                        <td class="px-6 py-3 font-semibold text-slate-900 dark:text-white">{{ $tech->name }}</td>
                        <td class="px-6 py-3 font-mono text-[11px] text-purple-400">{{ $tech->type->label() }}</td>
                        <td class="px-6 py-3 text-slate-500 dark:text-slate-400 font-mono">{{ $tech->projects_count }} projects</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="openAdd" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl space-y-4" @click.outside="openAdd = false">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Add Technology</h3>
            <form action="{{ route('admin.technologies.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Name *</label>
                    <input type="text" name="name" required placeholder="e.g. SvelteKit" class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Type *</label>
                    <select name="type" required class="mt-1 block w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-white focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                        @foreach($types as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openAdd = false" class="rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-white">Cancel</button>
                    <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-500">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection