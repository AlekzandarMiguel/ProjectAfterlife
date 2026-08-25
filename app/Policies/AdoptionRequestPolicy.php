<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\Project;
use App\Models\User;

class AdoptionRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AdoptionRequest $request): bool
    {
        return $user->isAdmin() || $request->user_id === $user->id || $request->project->owner_id === $user->id;
    }

    public function create(User $user, Project $project): bool
    {
        // Users cannot adopt their own project; project must be available
        return $user->isActive() && $project->owner_id !== $user->id && $project->isAvailable();
    }

    public function approve(User $user, AdoptionRequest $request): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user, AdoptionRequest $request): bool
    {
        return $user->isAdmin();
    }
}
