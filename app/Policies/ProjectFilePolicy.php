<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;

class ProjectFilePolicy
{
    public function view(?User $user, ProjectFile $file): bool
    {
        return true;
    }

    public function download(User $user, ProjectFile $file): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        $project = $file->project;
        if ($project->owner_id === $user->id || $project->original_owner_id === $user->id) {
            return true;
        }
        return $project->isAvailable() || $project->isResurrected();
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isAdmin() || $project->owner_id === $user->id;
    }

    public function delete(User $user, ProjectFile $file): bool
    {
        return $user->isAdmin() || ($file->project->owner_id === $user->id && $file->project->status->value === 'PENDING_REVIEW');
    }
}
