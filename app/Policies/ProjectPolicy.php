<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Project $project): bool
    {
        if ($project->isAvailable() || $project->isResurrected() || $project->isUnderRecovery()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->isAdmin() || $project->owner_id === $user->id || $project->original_owner_id === $user->id;
    }

    public function update(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->owner_id === $user->id && in_array($project->status->value, ['DRAFT', 'REVISION_REQUIRED', 'UNDER_RECOVERY', 'ADOPTED']);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    public function manageRecovery(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($project->owner_id === $user->id && $project->isUnderRecovery());
    }

    public function downloadFiles(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($project->owner_id === $user->id || $project->original_owner_id === $user->id) {
            return true;
        }
        return $project->isAvailable() || $project->isResurrected();
    }
}
