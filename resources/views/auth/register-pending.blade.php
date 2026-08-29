<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 text-slate-800 dark:text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($isApproved ?? false) ? 'Account Approved — Welcome to Project Afterlife!' : 'Registration Received — Project Afterlife' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-slate-950 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 relative overflow-x-hidden">

    <!-- Ambient background glow -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div id="ambient-glow" class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] {{ ($isApproved ?? false) ? 'bg-emerald-500/20' : 'bg-emerald-500/10' }} blur-[140px] rounded-full transition-all duration-700"></div>
        <div class="absolute bottom-0 right-10 w-[600px] h-[400px] bg-amber-500/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- Navigation Header -->
    <header class="w-full border-b border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100/80 backdrop-blur-md px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
            <x-logo-icon size="36" />
            <div class="flex flex-col">
                <span class="text-base font-bold text-slate-900 dark:text-white tracking-tight leading-tight">Project Afterlife</span>
                <span class="text-[10px] uppercase font-mono text-emerald-400 font-medium tracking-wider">Software Revival Platform</span>
            </div>
        </a>

        <a href="{{ route('login') }}" class="text-xs font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-400 transition flex items-center gap-1.5">
            <span>Sign In</span>
            <span>&rarr;</span>
        </a>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 flex items-center justify-center p-6 py-12">
        <div class="w-full max-w-lg mx-auto">
            <div id="status-card" class="bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden text-center transition-all duration-500">
                
                <!-- Dynamic Glow Decorator -->
                <div id="card-glow" class="absolute -right-16 -top-16 w-56 h-56 {{ ($isApproved ?? false) ? 'bg-emerald-500/20' : 'bg-amber-500/10' }} rounded-full blur-3xl pointer-events-none transition-all duration-700"></div>

                <!-- Status Icon Box -->
                <div id="icon-container" class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                    <div id="icon-bg" class="w-20 h-20 rounded-2xl {{ ($isApproved ?? false) ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-amber-500/10 border-amber-500/30 text-amber-400' }} border flex items-center justify-center transition-all duration-500">
                        <x-logo-icon size="44" />
                    </div>
                    <span id="icon-badge" class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full {{ ($isApproved ?? false) ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-slate-950' }} flex items-center justify-center text-xs font-bold ring-4 ring-slate-900 shadow-md transition-all duration-500">
                        {{ ($isApproved ?? false) ? '✓' : '⏳' }}
                    </span>
                </div>

                <!-- Status Badge -->
                <div id="badge-container" class="mb-4">
                    @if($isApproved ?? false)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-mono font-semibold bg-emerald-950/60 text-emerald-300 border border-emerald-800/50 shadow-lg shadow-emerald-950/40 animate-bounce">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            ✓ Account Verified & Approved
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-mono font-semibold bg-amber-950/60 text-amber-300 border border-amber-800/50">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            Verification In Progress
                        </span>
                    @endif
                </div>

                <!-- Title & Description -->
                <h1 id="title-text" class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    {{ ($isApproved ?? false) ? '🎉 Account Approved!' : 'Registration Received!' }}
                </h1>
                <p id="desc-text" class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed max-w-md mx-auto">
                    @if($isApproved ?? false)
                        Congratulations! Your developer account has been approved by the administration team. You now have full access to adopt abandoned projects and manage workspaces.
                    @else
                        Your developer account has been registered and submitted to the <strong class="text-slate-900 dark:text-white">Project Afterlife</strong> administration team for verification and approval.
                    @endif
                </p>

                <!-- Process Milestone Cards (Step 1, 2, 3) -->
                <div class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100/70 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 text-left mb-8 space-y-4">
                    <!-- Step 1 -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                            ✓
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-900 dark:text-white">1. Account & Profile Created</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Your credentials, bio, and initial developer profile are safely stored.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step-2-row" class="flex items-start gap-3.5">
                        @if($isApproved ?? false)
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                                ✓
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-emerald-300">2. Administrator Review Approved</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Verification completed. Identity verified and authorized.</p>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-amber-500/30 animate-pulse">
                                2
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-amber-300">2. Administrator Review</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">An administrator verifies new signups to protect repositories from unauthorized tampering or spam.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Step 3 -->
                    <div id="step-3-row" class="flex items-start gap-3.5">
                        @if($isApproved ?? false)
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                                ✓
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-emerald-300">3. Full Access Activated</p>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed">You can now adopt abandoned software, create version builds, and submit projects.</p>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-slate-300 dark:border-slate-700">
                                3
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">3. Full Access Activated</p>
                                <p class="text-[11px] text-slate-500 leading-relaxed">Once approved, you will be able to log in, adopt abandoned software, and access recovery workspaces.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div id="cta-container" class="flex flex-col sm:flex-row items-center gap-3">
                    @if($isApproved ?? false)
                        <a href="{{ route('login') }}" class="w-full py-3.5 px-5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition duration-150 shadow-xl shadow-emerald-950/60 flex items-center justify-center gap-2 animate-pulse">
                            <span>🚀 Sign In Now & Access Dashboard</span>
                            <span>&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:flex-1 py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition duration-150 shadow-lg shadow-emerald-950/50 flex items-center justify-center gap-2">
                            <span>Go to Sign In</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('explore.index') }}" class="w-full sm:flex-1 py-3 px-5 rounded-xl bg-slate-100/80 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:text-white border border-slate-300 dark:border-slate-700/60 font-semibold text-xs transition duration-150 flex items-center justify-center gap-2">
                            <span>Browse Public Projects</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-200 dark:border-slate-800/60 py-4 px-6 text-center text-xs text-slate-500 font-mono">
        Project Afterlife &bull; Dedicated to the Revival and Preservation of Abandoned Software
    </footer>

    <!-- Real-time Status Poller Script (Auto-updates without manual refresh when admin approves) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isInitiallyApproved = {{ ($isApproved ?? false) ? 'true' : 'false' }};
            const userEmail = '{{ $email ?? ($user->email ?? "") }}';

            if (isInitiallyApproved || !userEmail) {
                return;
            }

            // Poll every 3 seconds for instant live approval detection
            const pollInterval = setInterval(async () => {
                try {
                    const response = await fetch(`{{ route('register.check-status') }}?email=${encodeURIComponent(userEmail)}`, {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) return;

                    const data = await response.json();

                    if (data.is_approved) {
                        clearInterval(pollInterval);
                        applyApprovedState();
                    }
                } catch (e) {
                    console.log('Checking status...');
                }
            }, 3000);

            function applyApprovedState() {
                // 1. Ambient Glow
                const ambientGlow = document.getElementById('ambient-glow');
                if (ambientGlow) {
                    ambientGlow.className = "absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-emerald-500/25 blur-[140px] rounded-full transition-all duration-700";
                }

                // 2. Card Glow
                const cardGlow = document.getElementById('card-glow');
                if (cardGlow) {
                    cardGlow.className = "absolute -right-16 -top-16 w-56 h-56 bg-emerald-500/25 rounded-full blur-3xl pointer-events-none transition-all duration-700";
                }

                // 3. Icon
                const iconBg = document.getElementById('icon-bg');
                if (iconBg) {
                    iconBg.className = "w-20 h-20 rounded-2xl bg-emerald-500/10 border-emerald-500/30 text-emerald-400 border flex items-center justify-center transition-all duration-500 scale-110";
                }
                const iconBadge = document.getElementById('icon-badge');
                if (iconBadge) {
                    iconBadge.className = "absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold ring-4 ring-slate-900 shadow-md transition-all duration-500";
                    iconBadge.textContent = '✓';
                }

                // 4. Badge
                const badgeContainer = document.getElementById('badge-container');
                if (badgeContainer) {
                    badgeContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-mono font-semibold bg-emerald-950/60 text-emerald-300 border border-emerald-800/50 shadow-lg shadow-emerald-950/40 animate-bounce">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            ✓ Account Verified & Approved
                        </span>
                    `;
                }

                // 5. Title & Description
                document.getElementById('title-text').textContent = '🎉 Account Approved!';
                document.getElementById('desc-text').textContent = 'Congratulations! Your developer account has been approved by the administrator. You now have full access to adopt abandoned projects and manage workspaces.';

                // 6. Steps 2 & 3
                document.getElementById('step-2-row').innerHTML = `
                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                        ✓
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-300">2. Administrator Review Approved</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">Verification completed. Identity verified and authorized.</p>
                    </div>
                `;

                document.getElementById('step-3-row').innerHTML = `
                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-emerald-500/30">
                        ✓
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-300">3. Full Access Activated</p>
                        <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed">You can now adopt abandoned software, create version builds, and submit projects.</p>
                    </div>
                `;

                // 7. CTA Button
                document.getElementById('cta-container').innerHTML = `
                    <a href="{{ route('login') }}" class="w-full py-3.5 px-5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition duration-150 shadow-xl shadow-emerald-950/60 flex items-center justify-center gap-2 animate-pulse">
                        <span>🚀 Sign In Now & Access Dashboard</span>
                        <span>&rarr;</span>
                    </a>
                `;
            }
        });
    </script>
</body>
</html>