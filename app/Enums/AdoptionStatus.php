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
            self::PENDING => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
            self::REJECTED => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20',
            self::REVISION_REQUIRED => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20',
        };
    }
}
