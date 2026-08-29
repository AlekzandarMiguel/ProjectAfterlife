<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    public static function send(User $user, string $type, string $title, string $message, ?string $link = null, ?string $icon = 'bell'): void
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'icon' => $icon,
                'type' => $type,
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function notifyAdmins(string $type, string $title, string $message, ?string $link = null): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::send($admin, $type, $title, $message, $link, 'shield-alert');
        }
    }
}
