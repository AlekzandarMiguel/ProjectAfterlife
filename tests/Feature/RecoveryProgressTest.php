<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\RecoveryTask;
use App\Models\Category;
use App\Enums\UserRole;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskPhase;
use App\Services\RecoveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecoveryProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
    }

    public function test_dynamic_recovery_progress_calculates_accurately(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $category = Category::first();

        $project = Project::create([
            'title' => 'Recovery Progress Test Project',
            'slug' => 'recovery-progress-test-project',
            'short_description' => 'Test project',
            'description' => 'Test project description',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'reason_for_abandonment' => 'Testing calculations',
        ]);

        $service = app(RecoveryService::class);

        // 0 tasks = 0%
        $this->assertEquals(0, $service->calculateProgress($project));

        // Create 4 tasks: 1 completed, 3 pending => 25%
        RecoveryTask::create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'title' => 'Task 1',
            'phase' => TaskPhase::ASSESSMENT,
            'priority' => TaskPriority::HIGH,
            'status' => TaskStatus::COMPLETED,
        ]);

        RecoveryTask::create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'title' => 'Task 2',
            'phase' => TaskPhase::REPAIR,
            'priority' => TaskPriority::HIGH,
            'status' => TaskStatus::TODO,
        ]);

        RecoveryTask::create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'title' => 'Task 3',
            'phase' => TaskPhase::DEVELOPMENT,
            'priority' => TaskPriority::MEDIUM,
            'status' => TaskStatus::TODO,
        ]);

        RecoveryTask::create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'title' => 'Task 4',
            'phase' => TaskPhase::TESTING,
            'priority' => TaskPriority::LOW,
            'status' => TaskStatus::TODO,
        ]);

        $this->assertEquals(25.0, $service->calculateProgress($project));

        // Complete 2 more tasks => 3 completed of 4 => 75%
        RecoveryTask::where('project_id', $project->id)->where('title', 'Task 2')->update(['status' => TaskStatus::COMPLETED]);
        RecoveryTask::where('project_id', $project->id)->where('title', 'Task 3')->update(['status' => TaskStatus::COMPLETED]);

        $this->assertEquals(75.0, $service->calculateProgress($project));

        // Complete the last task => 4 completed of 4 => 100%
        RecoveryTask::where('project_id', $project->id)->where('title', 'Task 4')->update(['status' => TaskStatus::COMPLETED]);

        $this->assertEquals(100.0, $service->calculateProgress($project));
    }
}
