<button type="button" 
        x-data="{
            darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggle() {
                this.darkMode = !this.darkMode;
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            }
        }"
        @click="toggle()"
        class="group relative inline-flex items-center justify-center p-2 rounded-xl transition-all duration-200 cursor-pointer
               bg-white hover:bg-amber-50/80 border border-slate-200 hover:border-amber-300 text-slate-700 hover:text-amber-700 shadow-xs hover:shadow-sm
               dark:bg-slate-900/80 dark:hover:bg-slate-800 dark:border-slate-800 dark:hover:border-purple-500/50 dark:text-slate-300 dark:hover:text-purple-300 dark:hover:shadow-[0_0_15px_rgba(168,85,247,0.3)]"
        :title="darkMode ? 'Switch to Light Mode (Nordic Clean)' : 'Switch to Dark Mode (Cyber Luminescent)'"
        aria-label="Toggle Theme">
    
    <!-- Sun Icon (displayed in dark mode, pulses amber on hover) -->
    <svg x-show="darkMode" x-cloak class="w-4 h-4 text-amber-400 transition-transform duration-300 group-hover:rotate-45 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>

    <!-- Moon Icon (displayed in light mode, tilts on hover) -->
    <svg x-show="!darkMode" x-cloak class="w-4 h-4 text-slate-700 transition-transform duration-300 group-hover:-rotate-12 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
