<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectVersion;
use App\Models\User;

class ProjectVersionPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, ProjectVersion $version): bool
    {
        return true;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($project->owner_id === $user->id && $project->isUnderRecovery());
    }

    public function update(User $user, ProjectVersion $version): bool
    {
        return $user->isAdmin() || $version->project->owner_id === $user->id;
    }

    public function delete(User $user, ProjectVersion $version): bool
    {
        return $user->isAdmin();
    }
}
