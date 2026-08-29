<?php

namespace App\Services;

use App\Enums\FinalReviewStatus;
use App\Enums\ProjectStatus;
use App\Enums\TaskPhase;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\FinalReviewSubmission;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\ProjectVersion;
use App\Models\RecoveryTask;
use App\Models\RecoveryUpdate;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RecoveryService
{
    public function calculateProgress(Project $project): float
    {
        return (float) $project->recovery_progress;
    }

    public function createTask(Project $project, User $owner, array $data): RecoveryTask
    {
        $task = RecoveryTask::create([
            'project_id' => $project->id,
            'assigned_to' => $owner->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'phase' => $data['phase'] ?? TaskPhase::DEVELOPMENT,
            'priority' => $data['priority'] ?? TaskPriority::MEDIUM,
            'status' => TaskStatus::TODO,
            'due_date' => $data['due_date'] ?? null,
            'order_index' => $project->recoveryTasks()->count() + 1,
        ]);

        $project->update(['last_activity_at' => now()]);

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => $owner->id,
            'action' => 'TASK_CREATED',
            'old_status' => $project->status->value,
            'new_status' => $project->status->value,
            'description' => "New recovery task added: '{$task->title}' [{$task->phase->label()}].",
        ]);

        return $task;
    }

    public function updateTaskStatus(RecoveryTask $task, TaskStatus $status, User $user): void
    {
        $oldStatus = $task->status;
        $task->update([
            'status' => $status,
            'completed_at' => $status === TaskStatus::COMPLETED ? now() : null,
        ]);

        $project = $task->project;
        $project->update(['last_activity_at' => now()]);

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'action' => 'TASK_STATUS_UPDATED',
            'old_status' => $project->status->value,
            'new_status' => $project->status->value,
            'description' => "Task '{$task->title}' status changed from {$oldStatus->label()} to {$status->label()}. Current progress: {$project->recovery_progress}%.",
        ]);

        AuditService::log('RECOVERY_TASK_UPDATED', $task, ['status' => $status->value]);
    }

    public function addRecoveryUpdate(Project $project, User $user, string $title, string $text): RecoveryUpdate
    {
        $update = RecoveryUpdate::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'update_title' => $title,
            'update_text' => $text,
        ]);

        $project->update(['last_activity_at' => now()]);

        NotificationService::notifyAdmins(
            'recovery_update_posted',
            'Recovery Update Posted',
            "User {$user->name} posted a recovery progress update for '{$project->title}': '{$title}'",
            route('admin.recovery.index')
        );

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'action' => 'RECOVERY_NOTE_ADDED',
            'old_status' => $project->status->value,
            'new_status' => $project->status->value,
            'description' => "Recovery update posted: '{$title}'",
        ]);

        return $update;
    }

    public function releaseVersion(Project $project, User $owner, array $data, ?UploadedFile $sourceZip = null): ProjectVersion
    {
        return DB::transaction(function () use ($project, $owner, $data, $sourceZip) {
            $version = ProjectVersion::create([
                'project_id' => $project->id,
                'uploaded_by' => $owner->id,
                'version_number' => $data['version_number'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'release_notes' => $data['release_notes'],
                'is_final_release' => $data['is_final_release'] ?? false,
            ]);

            if ($sourceZip) {
                app(ProjectService::class)->storeFile(
                    $project,
                    $version,
                    $owner,
                    $sourceZip,
                    \App\Enums\FileType::SOURCE_CODE_ZIP
                );
            }

            $project->update(['last_activity_at' => now()]);

            NotificationService::notifyAdmins(
                'version_released',
                'New Version Released',
                "New version {$version->version_number} ('{$version->title}') was released for '{$project->title}' by {$owner->name}.",
                route('admin.projects.index')
            );

            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $owner->id,
                'action' => 'VERSION_RELEASED',
                'old_status' => $project->status->value,
                'new_status' => $project->status->value,
                'description' => "New project version released: {$version->version_number} - '{$version->title}'.",
            ]);

            AuditService::log('VERSION_RELEASED', $version, ['version' => $version->version_number]);

            return $version;
        });
    }

    public function submitFinalReview(Project $project, User $owner, array $data, ?ProjectVersion $finalVersion = null): FinalReviewSubmission
    {
        return DB::transaction(function () use ($project, $owner, $data, $finalVersion) {
            $submission = FinalReviewSubmission::create([
                'project_id' => $project->id,
                'version_id' => $finalVersion ? $finalVersion->id : ($project->latestVersion instanceof \App\Models\ProjectVersion ? $project->latestVersion->id : null),
                'submitted_by' => $owner->id,
                'completion_summary' => $data['completion_summary'],
                'completed_features' => $data['completed_features'],
                'testing_summary' => $data['testing_summary'],
                'status' => FinalReviewStatus::PENDING,
            ]);

            $oldStatus = $project->status->value;
            $project->update([
                'status' => ProjectStatus::PENDING_FINAL_REVIEW,
                'last_activity_at' => now(),
            ]);

            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $owner->id,
                'action' => 'FINAL_REVIEW_SUBMITTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::PENDING_FINAL_REVIEW->value,
                'description' => "Owner {$owner->name} submitted the recovered project for Administrator Final Resurrection Review.",
            ]);

            NotificationService::notifyAdmins(
                'final_review_submitted',
                'Final Resurrection Review Submitted',
                "Project '{$project->title}' was submitted for final verification and resurrection by {$owner->name}.",
                route('admin.final-reviews.show', $submission)
            );

            NotificationService::send(
                $owner,
                'final_review_submitted',
                'Final Review Submitted',
                "Your recovery completion report for '{$project->title}' has been submitted for admin verification.",
                route('user.recovery.workspace', $project)
            );

            AuditService::log('FINAL_REVIEW_SUBMITTED', $submission);

            return $submission;
        });
    }

    public function approveResurrection(FinalReviewSubmission $submission, User $admin, ?string $feedback = null): void
    {
        DB::transaction(function () use ($submission, $admin, $feedback) {
            $project = $submission->project;
            $oldStatus = $project->status->value;

            $submission->update([
                'status' => FinalReviewStatus::APPROVED,
                'admin_feedback' => $feedback,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            $project->update([
                'status' => ProjectStatus::RESURRECTED,
                'resurrected_at' => now(),
                'last_activity_at' => now(),
            ]);

            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $admin->id,
                'action' => 'PROJECT_RESURRECTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::RESURRECTED->value,
                'description' => "Project successfully verified and RESURRECTED! Full recovery certified by Administrator {$admin->name}.",
            ]);

            NotificationService::send(
                $project->owner,
                'project_resurrected',
                '🏆 Project Resurrected!',
                "Congratulations! '{$project->title}' has passed all reviews and is officially marked as RESURRECTED.",
                route('explore.show', $project)
            );

            NotificationService::send(
                $project->originalOwner,
                'project_resurrected',
                'Your Former Project is Resurrected!',
                "Great news! Your abandoned project '{$project->title}' has been fully recovered and resurrected by {$project->owner->name}.",
                route('explore.show', $project)
            );

            AuditService::log('PROJECT_RESURRECTED', $project, ['approved_by' => $admin->id]);
        });
    }
}
