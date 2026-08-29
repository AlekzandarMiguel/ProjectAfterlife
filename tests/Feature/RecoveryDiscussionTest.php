<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\RecoveryComment;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecoveryDiscussionTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $contributor;
    protected User $admin;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);

        $category = Category::first() ?? Category::create(['name' => 'General', 'slug' => 'general']);
        $this->owner = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $this->contributor = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $this->admin = User::factory()->create(['role' => UserRole::ADMIN, 'status' => UserStatus::ACTIVE]);

        $projectService = app(ProjectService::class);
        $this->project = $projectService->createProject([
            'title' => 'Discussion Test Project',
            'short_description' => 'Testing recovery comments',
            'description' => 'Full test description for workspace comment validation.',
            'category_id' => $category->id,
            'reason_for_abandonment' => 'Abandoned for testing.',
            'ownership_confirmed' => true,
        ], $this->owner);

        $projectService->approveProject($this->project, $this->admin, 'Approved');
        $this->project->update(['status' => ProjectStatus::UNDER_RECOVERY]);
    }

    public function test_user_can_post_recovery_workspace_comment(): void
    {
        $this->actingAs($this->contributor)
            ->post(route('user.recovery.comments.store', $this->project), [
                'comment' => 'Completed initial profiling on the legacy database schema.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('recovery_comments', [
            'project_id' => $this->project->id,
            'user_id' => $this->contributor->id,
            'comment' => 'Completed initial profiling on the legacy database schema.',
        ]);
    }

    public function test_author_or_admin_can_delete_comment(): void
    {
        $comment = RecoveryComment::create([
            'project_id' => $this->project->id,
            'user_id' => $this->contributor->id,
            'comment' => 'Comment to be removed.',
        ]);

        $this->actingAs($this->contributor)
            ->delete(route('user.recovery.comments.destroy', [$this->project, $comment]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('recovery_comments', [
            'id' => $comment->id,
        ]);
    }
}
