<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\AdoptionRequest;
use App\Models\Category;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Enums\ProjectStatus;
use App\Enums\AdoptionStatus;
use App\Services\AdoptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnershipTransferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
    }

    public function test_atomic_ownership_transfer_executes_correctly(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $originalOwner = User::factory()->create(['role' => UserRole::USER]);
        $adopter = User::factory()->create(['role' => UserRole::USER]);
        $category = Category::first();

        $project = Project::create([
            'title' => 'Abandoned Legacy API',
            'slug' => 'abandoned-legacy-api',
            'short_description' => 'A legacy REST API',
            'description' => 'Detailed description of the API',
            'category_id' => $category->id,
            'owner_id' => $originalOwner->id,
            'original_owner_id' => $originalOwner->id,
            'status' => ProjectStatus::AVAILABLE,
            'reason_for_abandonment' => 'Ran out of time',
        ]);

        $adoptionRequest = AdoptionRequest::create([
            'project_id' => $project->id,
            'user_id' => $adopter->id,
            'reason' => 'I love REST APIs and will rewrite it in modern PHP.',
            'proposed_improvements' => 'Port to PHP 8.2 with OpenAPI 3.0 spec',
            'recovery_plan' => 'Phase 1: audit, Phase 2: rewrite, Phase 3: tests',
            'expected_completion_date' => now()->addDays(30),
            'status' => AdoptionStatus::PENDING,
        ]);

        $service = app(AdoptionService::class);
        $transfer = $service->approveAdoptionAndTransferOwnership($adoptionRequest, $admin, 'Great proposal approved');

        $this->assertNotNull($transfer);

        // Verify project ownership is updated to adopter
        $project->refresh();
        $this->assertEquals($adopter->id, $project->owner_id);
        $this->assertEquals($originalOwner->id, $project->original_owner_id);
        $this->assertEquals(ProjectStatus::UNDER_RECOVERY, $project->status);

        // Verify Adoption Request is marked approved
        $adoptionRequest->refresh();
        $this->assertEquals(AdoptionStatus::APPROVED, $adoptionRequest->status);
        $this->assertEquals($admin->id, $adoptionRequest->reviewed_by);

        // Verify OwnershipTransfer database record is created
        $this->assertDatabaseHas('ownership_transfers', [
            'project_id' => $project->id,
            'previous_owner_id' => $originalOwner->id,
            'new_owner_id' => $adopter->id,
            'approved_by' => $admin->id,
            'transfer_status' => 'completed',
        ]);

        // Verify Project History has logged the event
        $this->assertDatabaseHas('project_history', [
            'project_id' => $project->id,
            'action' => 'OWNERSHIP_TRANSFERRED',
        ]);
    }
}
