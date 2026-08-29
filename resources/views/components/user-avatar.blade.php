@props([
    'user',
    'size' => 'w-8 h-8',
    'textSize' => 'text-xs',
    'class' => '',
])

@php
    $isAdmin = $user?->isAdmin() ?? false;
    $initials = $user?->initials ?? ($isAdmin ? 'ADM' : 'U');
    $name = $user?->name ?? ($isAdmin ? 'Administrator' : 'User');
    $hasCustomAvatar = !empty($user?->avatar) && !str_starts_with($user->avatar, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar);
    $avatarUrl = $hasCustomAvatar ? $user->avatar_url : ($user?->avatar && str_starts_with($user->avatar, 'http') ? $user->avatar : null);
@endphp

@if($isAdmin)
    <!-- Official Administrator Figure Icon -->
    <div class="relative inline-flex items-center justify-center shrink-0 rounded-full select-none bg-purple-100 dark:bg-purple-950/90 text-purple-700 dark:text-purple-300 border border-purple-300 dark:border-purple-700/60 shadow-xs {{ $size }} {{ $class }}" title="Platform Administrator">
        <svg class="w-3/5 h-3/5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>
@else
    <!-- Developer / User Avatar with Custom Photo or Initials -->
    <div class="relative inline-flex items-center justify-center shrink-0 rounded-full overflow-hidden select-none bg-emerald-800 text-white font-bold font-mono shadow-xs {{ $size }} {{ $class }}">
        @if($avatarUrl)
            <img 
                src="{{ $avatarUrl }}" 
                alt="{{ $name }}" 
                class="w-full h-full object-cover rounded-full"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            />
            <div class="w-full h-full hidden items-center justify-center bg-emerald-800 text-white font-bold font-mono {{ $textSize }}">
                {{ $initials }}
            </div>
        @else
            <!-- Human User Initials Badge -->
            <div class="w-full h-full flex items-center justify-center bg-emerald-800 text-white font-bold font-mono {{ $textSize }}">
                {{ $initials }}
            </div>
        @endif
    </div>
@endif
