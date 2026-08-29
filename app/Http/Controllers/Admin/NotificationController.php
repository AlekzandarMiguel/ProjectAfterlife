<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $adminId = auth()->id();
        $query = DB::table('notifications')
            ->where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', $adminId);

        $filter = $request->input('filter', 'all');
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $unreadCount = DB::table('notifications')
            ->where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', $adminId)
            ->whereNull('read_at')
            ->count();

        $notifications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->update(['read_at' => now()]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All administrator notifications marked as read.');
    }
}
