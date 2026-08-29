@extends('layouts.guest', ['title' => 'Project Afterlife — Software Revival & Preservation'])

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-20 pb-24 lg:pt-32 lg:pb-36 border-b border-slate-200 dark:border-slate-800">
    <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/5 via-transparent to-transparent pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 text-xs font-bold text-emerald-800 dark:text-emerald-300 shadow-xs">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Open-Source Software Resurrection Hub</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Give Abandoned Projects a <span class="text-emerald-600 dark:text-emerald-400">Second Life</span>.
            </h1>

            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                Project Afterlife connects original software creators who can no longer maintain their work with dedicated developers ready to adopt, restore, and maintain codebases under secure, transparent governance.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('explore.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <span>Explore Adoptable Projects</span>
                </a>
                @auth
                    <a href="{{ route('user.projects.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-6 py-3.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-900 transition shadow-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Upload Abandoned Project</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-6 py-3.5 text-xs font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-900 transition shadow-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                        <span>Register to Adopt</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- 4 Platform Stats -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-5 text-center shadow-xs theme-interactive-card">
                <div class="text-3xl font-bold text-slate-900 dark:text-white font-mono">{{ $stats['total_projects'] }}</div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase font-mono">Cataloged Projects</div>
            </div>
            <div class="rounded-2xl border border-emerald-200 dark:border-slate-800 bg-emerald-50/50 dark:bg-slate-900/40 p-5 text-center shadow-xs theme-interactive-card">
                <div class="text-3xl font-bold text-emerald-700 dark:text-emerald-400 font-mono">{{ $stats['available_projects'] }}</div>
                <div class="text-xs font-bold text-emerald-800 dark:text-emerald-400 mt-1 uppercase font-mono">Available for Adoption</div>
            </div>
            <div class="rounded-2xl border border-purple-200 dark:border-slate-800 bg-purple-50/50 dark:bg-slate-900/40 p-5 text-center shadow-xs theme-interactive-card">
                <div class="text-3xl font-bold text-purple-700 dark:text-purple-400 font-mono">{{ $stats['resurrected_projects'] }}</div>
                <div class="text-xs font-bold text-purple-800 dark:text-purple-400 mt-1 uppercase font-mono">Resurrected</div>
            </div>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-5 text-center shadow-xs theme-interactive-card">
                <div class="text-3xl font-bold text-slate-900 dark:text-white font-mono">{{ $stats['total_developers'] }}</div>
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase font-mono">Verified Developers</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 border-b border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-950/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-xs font-mono uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold">Structured Lifecycle</h2>
            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">How Project Afterlife Works</p>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                A formal 5-step process governed by atomic ownership transfers and administrator reviews.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 md:grid-cols-5 gap-5">
            <!-- Step 1 -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                <div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 font-mono text-xs font-bold mb-4 border border-amber-300 dark:border-amber-700/60 shadow-xs">1</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Upload</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Author submits abandoned source code, SQL dumps, reason for abandonment, and ownership declaration.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase font-medium">Stage 1</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/50">PENDING_REVIEW</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                <div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 font-mono text-xs font-bold mb-4 border border-emerald-300 dark:border-emerald-700/60 shadow-xs">2</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Verification</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Admin inspects code archives, security requirements, and verifies legitimacy before publishing.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase font-medium">Stage 2</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800/50">AVAILABLE</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                <div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300 font-mono text-xs font-bold mb-4 border border-purple-300 dark:border-purple-700/60 shadow-xs">3</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Adoption</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">A developer applies with a detailed recovery roadmap. Admin approves and triggers atomic ownership transfer.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase font-medium">Stage 3</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-100 dark:bg-purple-950/40 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-800/50">ADOPTED</span>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                <div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-950/50 text-blue-800 dark:text-blue-300 font-mono text-xs font-bold mb-4 border border-blue-300 dark:border-blue-700/60 shadow-xs">4</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Recovery</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">New owner creates task checklists, computes live progress, records notes, and releases version tags.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase font-medium">Stage 4</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-blue-100 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-800/50">UNDER_RECOVERY</span>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                <div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-100 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 font-mono text-xs font-bold mb-4 border border-teal-300 dark:border-teal-700/60 shadow-xs">5</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Resurrection</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Admin reviews completed features and test reports, granting permanent RESURRECTED certification.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase font-medium">Certified</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-teal-100 dark:bg-teal-950/40 text-teal-800 dark:text-teal-300 border border-teal-300 dark:border-teal-800/50">RESURRECTED</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Grid -->
<section class="py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Featured Projects in the Ecosystem</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Explore abandoned repositories currently awaiting adoption or undergoing active recovery.</p>
            </div>
            <a href="{{ route('explore.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                <span>View All Projects</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProjects as $project)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex flex-col justify-between shadow-xs theme-interactive-card">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[11px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400">{{ $project->category->name ?? 'General' }}</span>
                            <x-status-badge :status="$project->status" />
                        </div>
                        <a href="{{ route('explore.show', $project) }}" class="group block">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">{{ $project->title }}</h3>
                        </a>
                        <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                            {{ $project->short_description }}
                        </p>

                        <!-- Tech tags -->
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($project->technologies->take(4) as $tech)
                                <span class="rounded-md bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 text-[10px] font-mono font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/50">
                                    {{ $tech->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-700 dark:text-slate-300 font-mono">
                                {{ substr($project->author->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[120px]">{{ $project->author->name ?? 'Unknown' }}</span>
                        </div>
                        <a href="{{ route('explore.show', $project) }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Footer Section -->
<section class="py-16 bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-950 text-white relative overflow-hidden border-t border-slate-200 dark:border-slate-800">
    <div class="mx-auto max-w-4xl px-4 text-center space-y-6 relative z-10">
        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">Have a Project You Can No Longer Maintain?</h2>
        <p class="text-sm sm:text-base text-emerald-100 max-w-2xl mx-auto leading-relaxed">
            Preserve your legacy instead of letting your repositories gather dust. Hand it over to dedicated community maintainers under a formal, audited protocol.
        </p>
        <div class="pt-2">
            @auth
                <a href="{{ route('user.projects.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-xs font-bold text-slate-900 hover:bg-emerald-50 transition shadow-sm">
                    <span>Submit an Abandoned Project</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-xs font-bold text-slate-900 hover:bg-emerald-50 transition shadow-sm">
                    <span>Create an Account to Get Started</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection
