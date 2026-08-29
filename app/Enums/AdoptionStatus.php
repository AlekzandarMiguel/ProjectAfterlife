<?php

namespace App\Enums;

enum AdoptionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REVISION_REQUIRED = 'revision_required';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::REVISION_REQUIRED => 'Revision Requested',
        };
    }

        public function badgeClasses(): string
    {
        return match($this) {
            self::PENDING => 'bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/50',
            self::APPROVED => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800/50',
            self::REJECTED => 'bg-rose-100 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-800/50',
            self::REVISION_REQUIRED => 'bg-orange-100 dark:bg-orange-950/40 text-orange-800 dark:text-orange-300 border border-orange-300 dark:border-orange-800/50',
        };
    }
}
