@extends('layouts.guest', ['title' => 'Explore Abandoned Projects — Project Afterlife'])

@section('content')
<div class="py-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-8 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Project Repository</h1>
            <p class="text-xs text-slate-400 mt-1">Browse, search, and filter verified abandoned software projects available for community adoption.</p>
        </div>
        @auth
            <a href="{{ route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition shadow-sm self-start md:self-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Upload Abandoned Project</span>
            </a>
        @endauth
    </div>

    <!-- Search & Faceted Filter Grid -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter Form (Server-Side) -->
        <div class="lg:col-span-1">
            <form action="{{ route('explore.index') }}" method="GET" class="rounded-xl border border-slate-800 bg-slate-900/60 p-5 space-y-6">
                <!-- Search Query -->
                <div>
                    <label for="search" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Title, keyword, tech..." class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Project Status</label>
                    <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="">All Active Statuses</option>
                        <option value="AVAILABLE" {{ request('status') === 'AVAILABLE' ? 'selected' : '' }}>Available for Adoption</option>
                        <option value="UNDER_RECOVERY" {{ request('status') === 'UNDER_RECOVERY' ? 'selected' : '' }}>Under Recovery</option>
                        <option value="RESURRECTED" {{ request('status') === 'RESURRECTED' ? 'selected' : '' }}>Resurrected</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Category</label>
                    <select id="category" name="category" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Technology Filter -->
                <div>
                    <label for="technology" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Technology / Stack</label>
                    <select id="technology" name="technology" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="">All Technologies</option>
                        @foreach($technologies as $tech)
                            <option value="{{ $tech->id }}" {{ request('technology') == $tech->id ? 'selected' : '' }}>{{ $tech->name }} ({{ $tech->type->label() }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label for="sort" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider font-mono">Sort By</label>
                    <select id="sort" name="sort" class="mt-1.5 block w-full rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Recently Published</option>
                        <option value="activity" {{ request('sort') === 'activity' ? 'selected' : '' }}>Recent Activity</option>
                        <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Project Name (A-Z)</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest Submissions</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="w-full rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'status', 'category', 'technology', 'sort']))
                        <a href="{{ route('explore.index') }}" class="rounded-lg border border-slate-800 bg-slate-950 px-3 py-2 text-xs text-slate-400 hover:text-white transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Project Cards Grid -->
        <div class="lg:col-span-3 space-y-6">
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-5 flex flex-col justify-between hover:border-slate-700 transition">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="text-[11px] font-mono text-slate-400 truncate">{{ $project->category->name ?? 'General' }}</span>
                                    <x-status-badge :status="$project->status" />
                                </div>
                                <a href="{{ route('explore.show', $project) }}" class="group">
                                    <h3 class="text-base font-semibold text-white group-hover:text-emerald-400 transition line-clamp-1">{{ $project->title }}</h3>
                                </a>
                                <p class="mt-2 text-xs text-slate-400 line-clamp-3 leading-relaxed">
                                    {{ $project->short_description }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-800/80">
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach($project->technologies->take(3) as $tech)
                                        <span class="rounded bg-slate-800 px-2 py-0.5 text-[10px] font-mono text-slate-300">{{ $tech->name }}</span>
                                    @endforeach
                                    @if($project->technologies->count() > 3)
                                        <span class="rounded bg-slate-800/60 px-1.5 py-0.5 text-[10px] font-mono text-slate-400">+{{ $project->technologies->count() - 3 }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span class="truncate max-w-[120px]">By: <span class="text-slate-300">{{ $project->owner->name }}</span></span>
                                    <a href="{{ route('explore.show', $project) }}" class="text-emerald-400 font-medium hover:underline flex items-center gap-1">
                                        <span>Details</span>
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-800 text-slate-400 font-bold mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-base font-semibold text-white">No projects found</h3>
                    <p class="mt-2 text-xs text-slate-400 max-w-sm mx-auto">No abandoned projects match your current search and filter criteria.</p>
                    <div class="mt-6">
                        <a href="{{ route('explore.index') }}" class="rounded-lg border border-slate-800 bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                            Clear All Filters
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
