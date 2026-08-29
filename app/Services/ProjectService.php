<?php

namespace App\Services;

use App\Enums\FileType;
use App\Enums\ProjectStatus;
use App\Models\OwnershipDeclaration;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectHistory;
use App\Models\ProjectScreenshot;
use App\Models\ProjectVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectService
{
    public function createProject(array $data, User $user, array $files = [], array $screenshots = []): Project
    {
        return DB::transaction(function () use ($data, $user, $files, $screenshots) {
            $project = Project::create([
                'owner_id' => $user->id,
                'original_owner_id' => $user->id,
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::lower(Str::random(6)),
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'project_type' => $data['project_type'] ?? \App\Enums\ProjectType::WEB,
                'development_status' => $data['development_status'] ?? \App\Enums\DevelopmentStatus::PROTOTYPE,
                'reason_for_abandonment' => $data['reason_for_abandonment'],
                'original_development_date' => $data['original_development_date'] ?? null,
                'last_development_date' => $data['last_development_date'] ?? null,
                'status' => ProjectStatus::PENDING_REVIEW,
                'last_activity_at' => now(),
            ]);

            // Sync Technologies
            if (!empty($data['technologies'])) {
                $project->technologies()->sync($data['technologies']);
            }

            // Ownership Declaration
            OwnershipDeclaration::create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'declaration_text' => $data['declaration_text'] ?? "I confirm that I have the right to submit this software project to Project Afterlife and that the information provided is accurate.",
                'ip_address' => request()->ip(),
                'confirmed_at' => now(),
            ]);

            // Initial Version (v1.0.0 Original Abandoned Build)
            $version = ProjectVersion::create([
                'project_id' => $project->id,
                'uploaded_by' => $user->id,
                'version_number' => 'v1.0.0',
                'title' => 'Initial Submission Build',
                'description' => 'Original abandoned state as submitted by the author.',
                'release_notes' => 'Initial upload to Project Afterlife repository.',
            ]);

            // Handle Project Files (Stored securely in non-executable storage)
            if (!empty($files['source_zip']) && $files['source_zip'] instanceof UploadedFile) {
                $this->storeFile($project, $version, $user, $files['source_zip'], FileType::SOURCE_CODE_ZIP);
            }
            if (!empty($files['readme']) && $files['readme'] instanceof UploadedFile) {
                $this->storeFile($project, $version, $user, $files['readme'], FileType::README);
            }
            if (!empty($files['documentation']) && $files['documentation'] instanceof UploadedFile) {
                $this->storeFile($project, $version, $user, $files['documentation'], FileType::DOCUMENTATION);
            }
            if (!empty($files['database_sql']) && $files['database_sql'] instanceof UploadedFile) {
                $this->storeFile($project, $version, $user, $files['database_sql'], FileType::DATABASE_SQL);
            }

            // Handle Screenshots
            if (!empty($screenshots)) {
                $order = 0;
                foreach ($screenshots as $shot) {
                    if ($shot instanceof UploadedFile) {
                        $path = $shot->store('screenshots/' . $project->id, 'public');
                        ProjectScreenshot::create([
                            'project_id' => $project->id,
                            'image_path' => $path,
                            'caption' => 'Project Screenshot',
                            'order_index' => $order++,
                        ]);
                    }
                }
            }

            // Log Project History
            $this->logHistory($project, $user->id, 'SUBMITTED', null, ProjectStatus::PENDING_REVIEW->value, 'Project submitted for Administrator verification.');

            // Notifications
            NotificationService::notifyAdmins(
                'project_submitted',
                'New Project Submitted',
                "User {$user->name} submitted '{$project->title}' for review.",
                route('admin.projects.submissions.show', $project)
            );

            NotificationService::send(
                $user,
                'project_submitted',
                'Submission Received',
                "Your project '{$project->title}' was successfully submitted and is under admin review.",
                route('user.projects.show', $project)
            );

            // Audit
            AuditService::log('PROJECT_SUBMITTED', $project, ['title' => $project->title]);

            return $project;
        });
    }

    public function storeFile(Project $project, ?ProjectVersion $version, User $user, UploadedFile $file, FileType $type): ProjectFile
    {
        // 1. Sanitize original filename (strip path traversal, null bytes, dangerous characters)
        $rawFilename = basename($file->getClientOriginalName());
        $cleanFilename = preg_replace('/[^a-zA-Z0-9_\-\. ]/', '_', $rawFilename) ?? 'attachment';
        $safeExtension = strtolower($file->getClientOriginalExtension() ?: 'bin');

        // 2. Generate cryptographically safe UUID internal storage name
        $safeStorageName = (string) Str::uuid() . '.' . $safeExtension;

        // 3. Store on private disk (outside public webroot)
        $path = $file->storeAs('projects/' . $project->id . '/files', $safeStorageName, 'local');

        // 4. Archive Security Inspection & File Tree Extraction
        $sha256 = null;
        $fileTree = null;
        $securityStatus = 'clean';
        $isScanned = false;

        $absolutePath = Storage::disk('local')->path($path);
        if ($safeExtension === 'zip' || in_array($type, [FileType::SOURCE_CODE_ZIP, FileType::RELEASE_PACKAGE], true)) {
            $inspection = app(ArchiveInspectionService::class)->inspect($absolutePath);
            $sha256 = $inspection['sha256_hash'];
            $fileTree = $inspection['file_tree'];
            $securityStatus = $inspection['security_status'];
            $isScanned = true;
        } elseif (file_exists($absolutePath)) {
            $sha256 = hash_file('sha256', $absolutePath) ?: null;
            $isScanned = true;
        }

        return ProjectFile::create([
            'project_id' => $project->id,
            'version_id' => $version?->id,
            'uploaded_by' => $user->id,
            'file_name' => $cleanFilename,
            'storage_path' => $path,
            'file_type' => $type,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'sha256_hash' => $sha256,
            'file_tree_json' => $fileTree,
            'is_scanned' => $isScanned,
            'security_status' => $securityStatus,
            'scanned_at' => $isScanned ? now() : null,
            'is_current' => true,
        ]);
    }

    public function approveProject(Project $project, User $admin, ?string $notes = null): void
    {
        DB::transaction(function () use ($project, $admin, $notes) {
            $oldStatus = $project->status->value;
            $project->update([
                'status' => ProjectStatus::AVAILABLE,
                'admin_review_notes' => $notes,
                'published_at' => now(),
                'last_activity_at' => now(),
            ]);

            $this->logHistory($project, $admin->id, 'APPROVED', $oldStatus, ProjectStatus::AVAILABLE->value, 'Project verified and approved by Administrator. Now Available for Adoption.');

            NotificationService::send(
                $project->owner,
                'project_approved',
                'Project Approved & Published',
                "Congratulations! '{$project->title}' was approved and is now open for community adoption.",
                route('user.projects.show', $project)
            );

            AuditService::log('PROJECT_APPROVED', $project, ['approved_by' => $admin->id]);
        });
    }

    public function rejectProject(Project $project, User $admin, string $reason): void
    {
        DB::transaction(function () use ($project, $admin, $reason) {
            $oldStatus = $project->status->value;
            $project->update([
                'status' => ProjectStatus::REJECTED,
                'admin_review_notes' => $reason,
                'last_activity_at' => now(),
            ]);

            $this->logHistory($project, $admin->id, 'REJECTED', $oldStatus, ProjectStatus::REJECTED->value, "Project rejected. Reason: {$reason}");

            NotificationService::send(
                $project->owner,
                'project_rejected',
                'Project Submission Rejected',
                "Your project '{$project->title}' was rejected by the administrator. Reason: {$reason}",
                route('user.projects.show', $project)
            );

            AuditService::log('PROJECT_REJECTED', $project, ['reason' => $reason]);
        });
    }

    public function requestRevision(Project $project, User $admin, string $instructions): void
    {
        DB::transaction(function () use ($project, $admin, $instructions) {
            $oldStatus = $project->status->value;
            $project->update([
                'status' => ProjectStatus::REVISION_REQUIRED,
                'revision_instructions' => $instructions,
                'last_activity_at' => now(),
            ]);

            $this->logHistory($project, $admin->id, 'REVISION_REQUESTED', $oldStatus, ProjectStatus::REVISION_REQUIRED->value, "Revision requested. Instructions: {$instructions}");

            NotificationService::send(
                $project->owner,
                'revision_required',
                'Revision Required for Your Project',
                "Action needed for '{$project->title}'. Admin notes: {$instructions}",
                route('user.projects.edit', $project)
            );

            AuditService::log('PROJECT_REVISION_REQUESTED', $project, ['instructions' => $instructions]);
        });
    }

    public function logHistory(Project $project, ?int $userId, string $action, ?string $oldStatus, ?string $newStatus, string $description): ProjectHistory
    {
        return ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => $userId,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'description' => $description,
        ]);
    }
}
