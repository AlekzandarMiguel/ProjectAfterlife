<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::PENDING => 'Pending Verification',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::ACTIVE => 'bg-emerald-950/40 text-emerald-300 border-emerald-800/50',
            self::PENDING => 'bg-amber-950/40 text-amber-300 border-amber-800/50',
            self::SUSPENDED => 'bg-rose-950/40 text-rose-300 border-rose-800/50',
        };
    }
}
