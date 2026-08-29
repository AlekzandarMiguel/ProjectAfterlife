@extends('layouts.admin', ['title' => 'System Notifications — Admin Console', 'header' => 'System Notifications Hub'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Actions & Unread Counter Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 p-6 shadow-xl">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">System Activity & Governance Alerts</h1>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-500/20 text-purple-300 border border-purple-500/40">
                        {{ $unreadCount }} Unread
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                        All Caught Up
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time alerts for user signups, intake submissions, adoption requests, recoveries, and security events.</p>
        </div>

        <div class="flex items-center gap-3">
            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl border border-purple-500/40 bg-purple-950/40 hover:bg-purple-900/60 px-4 py-2 text-xs font-bold text-purple-200 transition shadow-sm">
                        Mark All as Read
                    </button>
                </form>
            @endif

            <div class="flex rounded-xl bg-slate-50 dark:bg-slate-950 p-1 border border-slate-200 dark:border-slate-800 text-xs">
                <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}" class="px-3 py-1.5 rounded-lg font-medium transition {{ $filter === 'all' ? 'bg-purple-600 text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-white' }}">All</a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded-lg font-medium transition {{ $filter === 'unread' ? 'bg-purple-600 text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-white' }}">Unread ({{ $unreadCount }})</a>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40 overflow-hidden shadow-xl">
        <div class="divide-y divide-slate-200 dark:divide-slate-800/60">
            @forelse($notifications as $notif)
                @php
                    $data = json_decode($notif->data, true) ?? [];
                    $isUnread = is_null($notif->read_at);
                @endphp
                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition {{ $isUnread ? 'bg-purple-950/20 border-l-4 border-l-purple-500' : 'hover:bg-slate-100/80 dark:hover:bg-slate-900/60' }}">
                    <div class="flex items-start gap-4">
                        <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $isUnread ? 'bg-purple-600/30 text-purple-300 /50' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $data['title'] ?? 'System Alert' }}</h3>
                                @if($isUnread)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/30 text-purple-200">NEW</span>
                                @endif
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">&bull; {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">{{ $data['message'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0 self-end sm:self-center">
                        @if(!empty($data['link']))
                            <a href="{{ $data['link'] }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 hover:border-slate-600 px-3.5 py-1.5 text-xs font-semibold text-slate-900 dark:text-white transition flex items-center gap-1.5 shadow-sm">
                                <span>Inspect</span>
                                <svg class="h-3.5 w-3.5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        @endif

                        @if($isUnread)
                            <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="Mark as read">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500 dark:text-slate-400 space-y-3">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">No notifications found.</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">All incoming system events, applications, and security updates will appear here.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/60">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection