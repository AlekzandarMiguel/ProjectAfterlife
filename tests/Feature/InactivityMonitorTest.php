<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class InactivityMonitorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_inactivity_command_dispatches_warnings_for_stalled_recoveries(): void
    {
        Config::set('afterlife.inactivity_threshold_days', 60);

        $author = User::factory()->create(['role' => UserRole::USER]);
        $adopter = User::factory()->create(['role' => UserRole::USER]);
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $category = Category::firstOrFail();

        // Stalled project past 60 days
        $stalledProject = Project::create([
            'title' => 'Stalled Recovery App',
            'slug' => 'stalled-recovery-app',
            'short_description' => 'Stalled app',
            'description' => 'Detailed description',
            'category_id' => $category->id,
            'owner_id' => $adopter->id,
            'original_owner_id' => $author->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'reason_for_abandonment' => 'Lack of time',
            'last_activity_at' => now()->subDays(75),
        ]);

        $this->artisan('afterlife:check-inactivity')
            ->expectsOutputToContain('Scanning active recoveries past 60 days of inactivity')
            ->expectsOutputToContain('Found 1 project(s) with stalled recovery activity')
            ->assertSuccessful();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'INACTIVITY_WARNING_DISPATCHED',
            'entity_id' => $stalledProject->id,
        ]);
    }
}
