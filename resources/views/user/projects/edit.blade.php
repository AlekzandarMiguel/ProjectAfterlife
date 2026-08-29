@extends('layouts.app', ['title' => 'Edit Project — Project Afterlife', 'header' => 'Edit Project'])

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-white tracking-tight">Edit Project: {{ $project->title }}</h1>
        <p class="text-xs text-slate-400 mt-1">Make required modifications and resubmit for administrator approval.</p>
    </div>

    @if($project->revision_instructions)
        <div class="rounded-xl border border-orange-500/40 bg-orange-950/20 p-4 text-xs text-orange-200 mb-6">
            <span class="font-bold">Admin Feedback:</span> {{ $project->revision_instructions }}
        </div>
    @endif

    <form action="{{ route('user.projects.update', $project) }}" method="POST" class="rounded-2xl border border-slate-800 bg-slate-900/60 p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Project Name *</label>
            <input type="text" id="title" name="title" required value="{{ old('title', $project->title) }}" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
        </div>

        <div>
            <label for="short_description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Short Description *</label>
            <textarea id="short_description" name="short_description" rows="2" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('short_description', $project->short_description) }}</textarea>
        </div>

        <div>
            <label for="description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Full Description & Architecture *</label>
            <textarea id="description" name="description" rows="6" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('description', $project->description) }}</textarea>
        </div>

        <div>
            <label for="category_id" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Category *</label>
            <select id="category_id" name="category_id" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="reason_for_abandonment" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Reason for Abandonment *</label>
            <textarea id="reason_for_abandonment" name="reason_for_abandonment" rows="3" required class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">{{ old('reason_for_abandonment', $project->reason_for_abandonment) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono mb-2">Technologies</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @php $currentTechIds = $project->technologies->pluck('id')->toArray(); @endphp
                @foreach($technologies as $type => $techList)
                    @foreach($techList as $tech)
                        <label class="flex items-center gap-2 rounded-lg border border-slate-800 bg-slate-950 p-2 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="technologies[]" value="{{ $tech->id }}" {{ in_array($tech->id, old('technologies', $currentTechIds)) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-800 bg-slate-900 text-emerald-600 focus:ring-emerald-500">
                            <span class="truncate">{{ $tech->name }}</span>
                        </label>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('user.projects.show', $project) }}" class="rounded-lg border border-slate-800 bg-slate-950 px-4 py-2.5 text-xs text-slate-400 hover:text-white transition">Cancel</a>
            <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">Save & Resubmit</button>
        </div>
    </form>
</div>
@endsection
