@extends('layouts.guest', ['title' => 'Project Afterlife — Give Abandoned Projects a Second Life'])

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-20 pb-28 border-b border-slate-800/60">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(16,185,129,0.15),rgba(255,255,255,0))]"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400 mb-8">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Web-Based Abandoned Software Recovery & Ownership Transfer System</span>
        </div>

        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl max-w-4xl mx-auto leading-tight">
            Give Abandoned Projects <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">a Second Life.</span>
        </h1>

        <p class="mt-6 text-base sm:text-lg leading-8 text-slate-400 max-w-2xl mx-auto">
            Project Afterlife connects developers with abandoned software projects so valuable architectures and ideas can be verified, adopted, recovered, and resurrected.
        </p>

        <div class="mt-10 flex items-center justify-center gap-4">
            <a href="{{ route('explore.index') }}" class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition flex items-center gap-2">
                <span>Explore Abandoned Projects</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
            @auth
                <a href="{{ route('user.projects.create') }}" class="rounded-lg bg-slate-900 border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-white transition">
                    Upload Abandoned Project
                </a>
            @else
                <a href="{{ route('register') }}" class="rounded-lg bg-slate-900 border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-white transition">
                    Submit a Project
                </a>
            @endauth
        </div>

        <!-- Telemetry Stats Strip -->
        <div class="mt-20 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-6 max-w-5xl mx-auto border-t border-slate-800/80 pt-10 text-left">
            <div class="p-3">
                <div class="text-2xl font-bold text-white font-mono">{{ $stats['total_projects'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Total Uploads</div>
            </div>
            <div class="p-3">
                <div class="text-2xl font-bold text-emerald-400 font-mono">{{ $stats['available_projects'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Available for Adoption</div>
            </div>
            <div class="p-3">
                <div class="text-2xl font-bold text-sky-400 font-mono">{{ $stats['active_recoveries'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Under Active Recovery</div>
            </div>
            <div class="p-3">
                <div class="text-2xl font-bold text-purple-400 font-mono">{{ $stats['resurrected_projects'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Resurrected Builds</div>
            </div>
            <div class="p-3">
                <div class="text-2xl font-bold text-slate-200 font-mono">{{ $stats['ownership_transfers'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Ownership Transfers</div>
            </div>
            <div class="p-3">
                <div class="text-2xl font-bold text-slate-200 font-mono">{{ $stats['total_developers'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Verified Developers</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 border-b border-slate-800/60 bg-slate-950/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold">Structured Lifecycle</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">How Project Afterlife Works</p>
            <p class="mt-4 text-sm text-slate-400">
                A formal 5-step process governed by atomic ownership transfers and administrator reviews.
            </p>
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800 text-emerald-400 font-mono text-sm font-bold mb-4">1</div>
                    <h3 class="text-base font-semibold text-white">Upload</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">Author submits abandoned source code, SQL dumps, reason for abandonment, and ownership declaration.</p>
                </div>
                <div class="mt-4 text-[10px] font-mono text-slate-400 uppercase tracking-wider">Status: PENDING_REVIEW</div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800 text-emerald-400 font-mono text-sm font-bold mb-4">2</div>
                    <h3 class="text-base font-semibold text-white">Verification</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">Admin inspects code archives, security requirements, and verifies legitimacy before publishing.</p>
                </div>
                <div class="mt-4 text-[10px] font-mono text-emerald-400 uppercase tracking-wider">Status: AVAILABLE</div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800 text-emerald-400 font-mono text-sm font-bold mb-4">3</div>
                    <h3 class="text-base font-semibold text-white">Adoption</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">A developer applies with a detailed recovery roadmap. Admin approves and triggers atomic ownership transfer.</p>
                </div>
                <div class="mt-4 text-[10px] font-mono text-amber-400 uppercase tracking-wider">Status: ADOPTED</div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800 text-emerald-400 font-mono text-sm font-bold mb-4">4</div>
                    <h3 class="text-base font-semibold text-white">Recovery</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">New owner creates task checklists, computes live progress, records notes, and releases version tags.</p>
                </div>
                <div class="mt-4 text-[10px] font-mono text-sky-400 uppercase tracking-wider">Status: UNDER_RECOVERY</div>
            </div>

            <div class="rounded-xl border border-purple-500/30 bg-purple-950/20 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500/20 text-purple-300 font-mono text-sm font-bold mb-4">5</div>
                    <h3 class="text-base font-semibold text-white">Resurrection</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">Admin reviews completed features and test reports, granting permanent RESURRECTED status.</p>
                </div>
                <div class="mt-4 text-[10px] font-mono text-purple-400 uppercase tracking-wider font-bold">Status: RESURRECTED</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Grid -->
<section class="py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Featured Projects in the Ecosystem</h2>
                <p class="text-sm text-slate-400 mt-1">Explore abandoned repositories currently awaiting adoption or undergoing active recovery.</p>
            </div>
            <a href="{{ route('explore.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1.5">
                <span>View All Projects</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProjects as $project)
                <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6 flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[11px] font-mono text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                            <x-status-badge :status="$project->status" />
                        </div>
                        <a href="{{ route('explore.show', $project) }}" class="group">
                            <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition">{{ $project->title }}</h3>
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
                            <div class="flex items-center gap-2">
                                <span>Owner:</span>
                                <span class="text-slate-200 font-medium">{{ $project->owner->name }}</span>
                            </div>
                            <a href="{{ route('explore.show', $project) }}" class="text-emerald-400 font-medium hover:underline">Inspect &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Resurrected Spotlight Section -->
@if($latestResurrected->count() > 0)
<section class="py-20 border-t border-slate-800/60 bg-purple-950/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <span class="flex h-3 w-3 rounded-full bg-purple-400"></span>
            <h2 class="text-xl font-bold text-white tracking-tight">Resurrected Hall of Fame</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($latestResurrected as $res)
                <div class="rounded-xl border border-purple-500/30 bg-slate-900/80 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="rounded bg-purple-500/10 border border-purple-500/30 px-2 py-0.5 text-[10px] font-mono text-purple-300 font-bold">RESURRECTED</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $res->resurrected_at?->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-base font-semibold text-white">{{ $res->title }}</h3>
                        <p class="mt-2 text-xs text-slate-400 line-clamp-3 leading-relaxed">{{ $res->short_description }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800">
                        <div class="grid grid-cols-2 gap-2 text-xs mb-4 bg-slate-950/60 p-2.5 rounded-lg border border-slate-800">
                            <div>
                                <div class="text-[10px] text-slate-400">Original Author:</div>
                                <div class="font-medium text-slate-300 truncate">{{ $res->originalOwner->name }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-emerald-400">Resurrected By:</div>
                                <div class="font-medium text-emerald-300 truncate">{{ $res->owner->name }}</div>
                            </div>
                        </div>
                        <a href="{{ route('explore.show', $res) }}" class="block text-center rounded-lg bg-purple-600/20 border border-purple-500/40 py-2 text-xs font-semibold text-purple-300 hover:bg-purple-600/30 transition">
                            View Resurrection Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
