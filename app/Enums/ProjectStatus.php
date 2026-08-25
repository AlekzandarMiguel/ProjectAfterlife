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

    public function badgeClasses(): string
    {
        return match($this) {
            self::DRAFT => 'bg-slate-800 text-slate-300 border-slate-700',
            self::PENDING_REVIEW => 'bg-amber-950/40 text-amber-300 border-amber-800/50',
            self::AVAILABLE => 'bg-emerald-950/40 text-emerald-300 border-emerald-800/50',
            self::ADOPTION_PENDING => 'bg-purple-950/40 text-purple-300 border-purple-800/50',
            self::ADOPTED, self::UNDER_RECOVERY => 'bg-blue-950/40 text-blue-300 border-blue-800/50',
            self::INACTIVE => 'bg-yellow-950/40 text-yellow-300 border-yellow-800/50',
            self::ABANDONED_AGAIN => 'bg-orange-950/40 text-orange-300 border-orange-800/50',
            self::PENDING_FINAL_REVIEW => 'bg-indigo-950/40 text-indigo-300 border-indigo-800/50',
            self::RESURRECTED => 'bg-teal-950/40 text-teal-300 border-teal-800/50',
            self::REJECTED => 'bg-rose-950/40 text-rose-300 border-rose-800/50',
            self::REVISION_REQUIRED => 'bg-orange-950/40 text-orange-300 border-orange-800/50',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::PENDING_REVIEW => 'amber',
            self::AVAILABLE => 'emerald',
            self::ADOPTION_PENDING => 'purple',
            self::ADOPTED, self::UNDER_RECOVERY => 'blue',
            self::INACTIVE => 'yellow',
            self::ABANDONED_AGAIN => 'orange',
            self::PENDING_FINAL_REVIEW => 'indigo',
            self::RESURRECTED => 'teal',
            self::REJECTED => 'rose',
            self::REVISION_REQUIRED => 'orange',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::DRAFT => 'Work in progress, not yet submitted for review.',
            self::PENDING_REVIEW => 'Submitted by author, awaiting Administrator verification.',
            self::AVAILABLE => 'Verified abandoned software open for community developer adoption.',
            self::ADOPTION_PENDING => 'Adoption application submitted and under admin review.',
            self::ADOPTED => 'Adopted by a developer, transitioning to active recovery.',
            self::UNDER_RECOVERY => 'Actively being refactored, modernized, and fixed.',
            self::INACTIVE => 'Recovery progress stalled. Subject to reassignment if inactive.',
            self::ABANDONED_AGAIN => 'Developer abandoned recovery. Project returned to public pool.',
            self::PENDING_FINAL_REVIEW => 'Modernized project submitted for final Resurrection Certification.',
            self::RESURRECTED => 'Fully restored, verified, and active again in the open source ecosystem.',
            self::REJECTED => 'Did not meet repository quality or integrity standards.',
            self::REVISION_REQUIRED => 'Administrator requested revisions or additional details.',
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
