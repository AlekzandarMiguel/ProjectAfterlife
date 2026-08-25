<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\RecoveryTask;
use App\Models\User;

class RecoveryTaskPolicy
{
    public function view(User $user, RecoveryTask $task): bool
    {
        return $user->isAdmin() || $task->project->owner_id === $user->id || $task->assigned_to === $user->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($project->owner_id === $user->id && $project->isUnderRecovery());
    }

    public function update(User $user, RecoveryTask $task): bool
    {
        return $user->isAdmin() || $task->project->owner_id === $user->id || $task->assigned_to === $user->id;
    }

    public function delete(User $user, RecoveryTask $task): bool
    {
        return $user->isAdmin() || $task->project->owner_id === $user->id;
    }
}
