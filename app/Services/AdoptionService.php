<?php

namespace App\Services;

use App\Enums\AdoptionStatus;
use App\Enums\ProjectStatus;
use App\Models\AdoptionRequest;
use App\Models\OwnershipTransfer;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AdoptionService
{
    public function submitAdoptionRequest(Project $project, User $applicant, array $data): AdoptionRequest
    {
        if (!$project->canBeAdoptedBy($applicant)) {
            throw new Exception('This project cannot be adopted by you or is not currently available.');
        }

        return DB::transaction(function () use ($project, $applicant, $data) {
            $request = AdoptionRequest::create([
                'project_id' => $project->id,
                'user_id' => $applicant->id,
                'reason' => $data['reason'],
                'proposed_improvements' => $data['proposed_improvements'],
                'recovery_plan' => $data['recovery_plan'],
                'expected_completion_date' => $data['expected_completion_date'],
                'relevant_skills' => $data['relevant_skills'] ?? null,
                'status' => AdoptionStatus::PENDING,
            ]);

            $oldStatus = $project->status->value;
            $project->update([
                'status' => ProjectStatus::ADOPTION_PENDING,
                'last_activity_at' => now(),
            ]);

            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $applicant->id,
                'action' => 'ADOPTION_REQUESTED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::ADOPTION_PENDING->value,
                'description' => "User {$applicant->name} submitted an adoption proposal to recover this project.",
            ]);

            NotificationService::notifyAdmins(
                'adoption_requested',
                'New Adoption Request',
                "User {$applicant->name} requested to adopt '{$project->title}'.",
                route('admin.adoption-requests.show', $request)
            );

            NotificationService::send(
                $project->owner,
                'adoption_requested',
                'Adoption Request Received',
                "A developer applied to adopt your abandoned project '{$project->title}'.",
                route('user.projects.show', $project)
            );

            AuditService::log('ADOPTION_REQUESTED', $request, ['project_id' => $project->id, 'applicant_id' => $applicant->id]);

            return $request;
        });
    }

    /**
     * CRITICAL ATOMIC OWNERSHIP TRANSFER TRANSACTION
     */
    public function approveAdoptionAndTransferOwnership(AdoptionRequest $request, User $admin, ?string $adminNotes = null): OwnershipTransfer
    {
        return DB::transaction(function () use ($request, $admin, $adminNotes) {
            // 1. Lock the project with pessimistic row lock
            $project = Project::where('id', $request->project_id)->lockForUpdate()->firstOrFail();

            // 2. Lock the adoption request with pessimistic row lock
            $lockedRequest = AdoptionRequest::where('id', $request->id)->lockForUpdate()->firstOrFail();

            // 3. Verify request is still valid and pending
            if ($lockedRequest->status !== AdoptionStatus::PENDING) {
                throw new Exception('Adoption request is not in pending status.');
            }

            // 4. Verify project is still eligible for adoption
            if (!in_array($project->status, [ProjectStatus::AVAILABLE, ProjectStatus::ADOPTION_PENDING])) {
                throw new Exception('Project is not in an eligible status for adoption transfer.');
            }

            $previousOwner = $project->owner;
            $newOwner = $lockedRequest->applicant;

            // 5. Verify applicant is active and not current owner
            if (!$newOwner->isActive()) {
                throw new Exception('Applicant account is not active.');
            }

            if ($previousOwner->id === $newOwner->id) {
                throw new Exception('Cannot transfer ownership to the current owner.');
            }

            // 1. Create Immutable Ownership Transfer Record
            $transfer = OwnershipTransfer::create([
                'project_id' => $project->id,
                'previous_owner_id' => $previousOwner->id,
                'new_owner_id' => $newOwner->id,
                'adoption_request_id' => $request->id,
                'approved_by' => $admin->id,
                'transfer_reason' => $request->reason,
                'transfer_status' => 'completed',
                'transferred_at' => now(),
            ]);

            // 2. Update Adoption Request
            $request->update([
                'status' => AdoptionStatus::APPROVED,
                'admin_notes' => $adminNotes,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // 3. Transfer Project Ownership & Change Status to UNDER_RECOVERY
            $oldStatus = $project->status->value;
            $project->update([
                'owner_id' => $newOwner->id,
                'status' => ProjectStatus::UNDER_RECOVERY,
                'last_activity_at' => now(),
            ]);

            // 4. Record Immutable Project History
            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $admin->id,
                'action' => 'OWNERSHIP_TRANSFERRED',
                'old_status' => $oldStatus,
                'new_status' => ProjectStatus::UNDER_RECOVERY->value,
                'description' => "Ownership transferred from original owner {$previousOwner->name} to new owner {$newOwner->name} by Administrator {$admin->name}. Project is now Under Recovery.",
            ]);

            // 5. Send Notifications
            NotificationService::send(
                $newOwner,
                'adoption_approved',
                '🎉 Adoption Approved — You are the New Owner!',
                "Your adoption request for '{$project->title}' was approved. You can now access your Recovery Workspace.",
                route('user.recovery.workspace', $project)
            );

            NotificationService::send(
                $previousOwner,
                'ownership_transferred',
                'Project Ownership Transferred',
                "Your project '{$project->title}' has been successfully adopted by {$newOwner->name}.",
                route('user.projects.show', $project)
            );

            // 6. Record Audit Log
            AuditService::log('OWNERSHIP_TRANSFER_COMPLETED', $transfer, [
                'project_id' => $project->id,
                'previous_owner_id' => $previousOwner->id,
                'new_owner_id' => $newOwner->id,
                'approved_by' => $admin->id,
            ]);

            return $transfer;
        });
    }

    public function rejectAdoptionRequest(AdoptionRequest $request, User $admin, string $reason): void
    {
        DB::transaction(function () use ($request, $admin, $reason) {
            $project = $request->project;

            $request->update([
                'status' => AdoptionStatus::REJECTED,
                'admin_notes' => $reason,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // Return project to AVAILABLE if there are no other pending requests
            $hasOtherPending = AdoptionRequest::where('project_id', $project->id)
                ->where('id', '!=', $request->id)
                ->where('status', AdoptionStatus::PENDING)
                ->exists();

            if (!$hasOtherPending) {
                $oldStatus = $project->status->value;
                $project->update([
                    'status' => ProjectStatus::AVAILABLE,
                    'last_activity_at' => now(),
                ]);

                ProjectHistory::create([
                    'project_id' => $project->id,
                    'user_id' => $admin->id,
                    'action' => 'ADOPTION_REJECTED',
                    'old_status' => $oldStatus,
                    'new_status' => ProjectStatus::AVAILABLE->value,
                    'description' => "Adoption request from {$request->applicant->name} was rejected. Project returned to Available status.",
                ]);
            }

            NotificationService::send(
                $request->applicant,
                'adoption_rejected',
                'Adoption Request Declined',
                "Your adoption request for '{$project->title}' was not approved. Feedback: {$reason}",
                route('user.projects.show', $project)
            );

            AuditService::log('ADOPTION_REJECTED', $request, ['reason' => $reason]);
        });
    }
}
