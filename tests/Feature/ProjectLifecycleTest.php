<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Models\FinalReviewSubmission;
use App\Enums\UserRole;
use App\Enums\ProjectStatus;
use App\Enums\FinalReviewStatus;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
    }

    public function test_full_project_lifecycle_from_upload_to_resurrection(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $uploader = User::factory()->create(['role' => UserRole::USER]);
        $category = Category::first();
        $projectService = app(ProjectService::class);

        // 1. Uploader submits project -> status is PENDING_REVIEW
        $project = $projectService->createProject([
            'title' => 'Complete Lifecycle Project',
            'short_description' => 'Testing entire lifecycle',
            'description' => 'Full architectural breakdown of lifecycle test',
            'category_id' => $category->id,
            'reason_for_abandonment' => 'Finished initial prototype but left project',
            'ownership_confirmed' => true,
        ], $uploader);

        $this->assertEquals(ProjectStatus::PENDING_REVIEW, $project->status);
        $this->assertEquals($uploader->id, $project->owner_id);
        $this->assertEquals($uploader->id, $project->original_owner_id);

        // 2. Admin reviews & approves submission -> status is AVAILABLE
        $projectService->approveProject($project, $admin, 'Valid open submission');
        $project->refresh();
        $this->assertEquals(ProjectStatus::AVAILABLE, $project->status);

        // 3. User submits for Final Review after recovery -> status becomes PENDING_FINAL_REVIEW
        $finalReview = FinalReviewSubmission::create([
            'project_id' => $project->id,
            'submitted_by' => $uploader->id,
            'completion_summary' => 'All bugs fixed and tests written.',
            'completed_features' => 'Feature 1, Feature 2, Feature 3',
            'testing_summary' => '100% test coverage with Pest/PHPUnit',
            'status' => FinalReviewStatus::PENDING,
        ]);

        $project->update(['status' => ProjectStatus::PENDING_FINAL_REVIEW]);
        $this->assertEquals(ProjectStatus::PENDING_FINAL_REVIEW, $project->status);

        // 4. Admin approves Final Review -> status becomes RESURRECTED
        $finalReview->update([
            'status' => FinalReviewStatus::APPROVED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_feedback' => 'Exceptional resurrection work!',
        ]);

        $project->update([
            'status' => ProjectStatus::RESURRECTED,
            'resurrected_at' => now(),
        ]);

        $project->refresh();
        $this->assertEquals(ProjectStatus::RESURRECTED, $project->status);
        $this->assertNotNull($project->resurrected_at);
    }
}
