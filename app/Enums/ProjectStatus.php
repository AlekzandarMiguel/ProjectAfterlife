<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'DRAFT';
    case PENDING_REVIEW = 'PENDING_REVIEW';
    case REVISION_REQUIRED = 'REVISION_REQUIRED';
    case REJECTED = 'REJECTED';
    case AVAILABLE = 'AVAILABLE';
    case ADOPTION_PENDING = 'ADOPTION_PENDING';
    case ADOPTED = 'ADOPTED';
    case UNDER_RECOVERY = 'UNDER_RECOVERY';
    case PENDING_FINAL_REVIEW = 'PENDING_FINAL_REVIEW';
    case RESURRECTED = 'RESURRECTED';
    case INACTIVE = 'INACTIVE';
    case ABANDONED_AGAIN = 'ABANDONED_AGAIN';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Pending Review',
            self::REVISION_REQUIRED => 'Revision Required',
            self::REJECTED => 'Rejected',
            self::AVAILABLE => 'Available for Adoption',
            self::ADOPTION_PENDING => 'Adoption Pending',
            self::ADOPTED => 'Adopted',
            self::UNDER_RECOVERY => 'Under Recovery',
            self::PENDING_FINAL_REVIEW => 'Pending Final Review',
            self::RESURRECTED => 'Resurrected',
            self::INACTIVE => 'Inactive',
            self::ABANDONED_AGAIN => 'Abandoned Again',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::AVAILABLE => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30',
            self::UNDER_RECOVERY, self::ADOPTED => 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-600/20 dark:bg-sky-950/50 dark:text-sky-400 dark:ring-sky-500/30',
            self::RESURRECTED => 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20 dark:bg-purple-950/50 dark:text-purple-400 dark:ring-purple-500/30',
            self::PENDING_REVIEW, self::ADOPTION_PENDING, self::PENDING_FINAL_REVIEW => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-950/50 dark:text-amber-400 dark:ring-amber-500/30',
            self::REVISION_REQUIRED => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20 dark:bg-orange-950/50 dark:text-orange-400 dark:ring-orange-500/30',
            self::REJECTED, self::ABANDONED_AGAIN => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20 dark:bg-rose-950/50 dark:text-rose-400 dark:ring-rose-500/30',
            self::INACTIVE, self::DRAFT => 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::AVAILABLE;
    }

    public function isUnderRecovery(): bool
    {
        return in_array($this, [self::ADOPTED, self::UNDER_RECOVERY]);
    }
}
