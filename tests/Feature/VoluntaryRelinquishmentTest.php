<?php

namespace Tests\Feature;

use App\Enums\DevelopmentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoluntaryRelinquishmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_adopter_can_voluntarily_relinquish_stewardship(): void
    {
        $originalAuthor = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $adopter = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Project to Relinquish',
            'slug' => 'project-to-relinquish',
            'short_description' => 'A project under recovery.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $adopter->id,
            'original_owner_id' => $originalAuthor->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'project_type' => ProjectType::WEB,
            'development_status' => DevelopmentStatus::ALPHA,
            'reason_for_abandonment' => 'Original author moved to new position.',
        ]);

        $response = $this->actingAs($adopter)->post(route('user.recovery.relinquish', $project), [
            'relinquish_reason' => 'I have relocated and no longer have bandwidth to maintain this engine.',
        ]);

        $response->assertRedirect(route('user.projects.index'));

        $project->refresh();
        $this->assertEquals(ProjectStatus::AVAILABLE, $project->status);
        $this->assertEquals($originalAuthor->id, $project->owner_id);

        $this->assertDatabaseHas('project_history', [
            'project_id' => $project->id,
            'action' => 'STEWARDSHIP_RELINQUISHED',
            'user_id' => $adopter->id,
        ]);
    }

    public function test_unauthorized_user_cannot_relinquish_project(): void
    {
        $author = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $intruder = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Protected Project',
            'slug' => 'protected-project',
            'short_description' => 'Protected short description.',
            'description' => 'Protected description.',
            'category_id' => $category->id,
            'owner_id' => $author->id,
            'original_owner_id' => $author->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'project_type' => ProjectType::WEB,
            'development_status' => DevelopmentStatus::ALPHA,
            'reason_for_abandonment' => 'Lack of maintainer time.',
        ]);

        $response = $this->actingAs($intruder)->post(route('user.recovery.relinquish', $project), [
            'relinquish_reason' => 'Unauthorized attempt',
        ]);

        $response->assertStatus(403);
    }
}
