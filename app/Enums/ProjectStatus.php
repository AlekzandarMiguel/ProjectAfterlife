<?php

namespace App\Enums;

use App\Models\User;
use Exception;

enum ProjectStatus: string
{
    case DRAFT = 'DRAFT';
    case PENDING_REVIEW = 'PENDING_REVIEW';
    case AVAILABLE = 'AVAILABLE';
    case ADOPTION_PENDING = 'ADOPTION_PENDING';
    case ADOPTED = 'ADOPTED';
    case UNDER_RECOVERY = 'UNDER_RECOVERY';
    case INACTIVE = 'INACTIVE';
    case ABANDONED_AGAIN = 'ABANDONED_AGAIN';
    case PENDING_FINAL_REVIEW = 'PENDING_FINAL_REVIEW';
    case RESURRECTED = 'RESURRECTED';
    case REJECTED = 'REJECTED';
    case REVISION_REQUIRED = 'REVISION_REQUIRED';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Pending Review',
            self::AVAILABLE => 'Available for Adoption',
            self::ADOPTION_PENDING => 'Adoption Pending',
            self::ADOPTED => 'Adopted',
            self::UNDER_RECOVERY => 'Under Active Recovery',
            self::INACTIVE => 'Recovery Inactive',
            self::ABANDONED_AGAIN => 'Re-abandoned',
            self::PENDING_FINAL_REVIEW => 'Pending Resurrection Review',
            self::RESURRECTED => 'Certified Resurrected',
            self::REJECTED => 'Rejected',
            self::REVISION_REQUIRED => 'Revision Requested',
        };
    }

    public function canTransitionTo(ProjectStatus $target, User $actor): bool
    {
        if ($this === $target) {
            return true;
        }

        // Admin Transitions
        if ($actor->isAdmin()) {
            return match($this) {
                self::PENDING_REVIEW => in_array($target, [self::AVAILABLE, self::REJECTED, self::REVISION_REQUIRED]),
                self::AVAILABLE => in_array($target, [self::UNDER_RECOVERY, self::ADOPTED, self::ADOPTION_PENDING]),
                self::ADOPTION_PENDING => in_array($target, [self::AVAILABLE, self::UNDER_RECOVERY, self::ADOPTED]),
                self::ADOPTED, self::UNDER_RECOVERY => in_array($target, [self::AVAILABLE, self::INACTIVE, self::ABANDONED_AGAIN, self::PENDING_FINAL_REVIEW]),
                self::INACTIVE, self::ABANDONED_AGAIN => in_array($target, [self::AVAILABLE, self::UNDER_RECOVERY]),
                self::PENDING_FINAL_REVIEW => in_array($target, [self::RESURRECTED, self::UNDER_RECOVERY, self::REJECTED]),
                self::REVISION_REQUIRED => in_array($target, [self::PENDING_REVIEW, self::AVAILABLE]),
                default => false,
            };
        }

        // Developer / User Transitions
        return match($this) {
            self::DRAFT => $target === self::PENDING_REVIEW,
            self::REVISION_REQUIRED => $target === self::PENDING_REVIEW,
            self::ADOPTED, self::UNDER_RECOVERY => $target === self::PENDING_FINAL_REVIEW,
            default => false,
        };
    }

    public function assertCanTransitionTo(ProjectStatus $target, User $actor): void
    {
        if (!$this->canTransitionTo($target, $actor)) {
            throw new Exception("Unauthorized or invalid status transition from {$this->value} to {$target->value} for role {$actor->role->value}.");
        }
    }
}
