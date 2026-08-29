<?php

namespace App\Enums;

enum FinalReviewStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REVISION_REQUIRED = 'revision_required';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Admin Review',
            self::APPROVED => 'Resurrection Approved',
            self::REVISION_REQUIRED => 'Revision Requested',
            self::REJECTED => 'Rejected',
        };
    }
}
