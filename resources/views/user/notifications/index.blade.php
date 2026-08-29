@extends('layouts.app', ['title' => 'Notifications — Project Afterlife', 'header' => 'Notifications'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-lg font-bold text-white tracking-tight">System Notifications</h2>
            <p class="text-xs text-slate-400 mt-0.5">Stay updated on your project reviews, adoption requests, and ownership transfers.</p>
        </div>

        <form action="{{ route('user.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="rounded-lg border border-slate-800 bg-slate-900 px-3 py-1.5 text-xs text-slate-300 hover:text-white transition">
                Mark All as Read
            </button>
        </form>
    </div>

    @if($notifications->count() > 0)
        <div class="divide-y divide-slate-800 border border-slate-800 bg-slate-900/40 rounded-xl overflow-hidden">
            @foreach($notifications as $n)
                @php $data = json_decode($n->data, true); @endphp
                <div class="p-4 flex items-start justify-between gap-4 {{ is_null($n->read_at) ? 'bg-emerald-950/20' : '' }}">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-white">{{ $data['title'] ?? 'Notification' }}</span>
                            @if(is_null($n->read_at))
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ $data['message'] ?? '' }}</p>
                        <div class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @if(!empty($data['link']))
                            <a href="{{ $data['link'] }}" class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-500 transition">
                                View &rarr;
                            </a>
                        @endif

                        @if(is_null($n->read_at))
                            <form action="{{ route('user.notifications.read', $n->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-slate-400 hover:text-white text-xs">
                                    Mark read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">{{ $notifications->links() }}</div>
    @else
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-12 text-center">
            <p class="text-xs text-slate-400">You have no notifications.</p>
        </div>
    @endif
</div>
@endsection
