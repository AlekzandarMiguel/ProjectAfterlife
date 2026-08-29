<?php

namespace App\Policies;

use App\Models\OwnershipTransfer;
use App\Models\User;

class OwnershipTransferPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, OwnershipTransfer $transfer): bool
    {
        return $user->isAdmin() || $transfer->previous_owner_id === $user->id || $transfer->new_owner_id === $user->id;
    }
}
