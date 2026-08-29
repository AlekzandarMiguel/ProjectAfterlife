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

class ProjectWatchlistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_user_can_toggle_project_bookmark(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Watchable Engine',
            'slug' => 'watchable-engine',
            'short_description' => 'Short description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::AVAILABLE,
            'project_type' => ProjectType::WEB,
            'development_status' => DevelopmentStatus::ALPHA,
            'reason_for_abandonment' => 'Original developer retired.',
        ]);

        // Toggle ON
        $response = $this->actingAs($user)->post(route('user.bookmarks.toggle', $project));
        $response->assertRedirect();
        $this->assertTrue($project->isBookmarkedBy($user));

        // View Watchlist Index
        $indexResponse = $this->actingAs($user)->get(route('user.bookmarks.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Watchable Engine');

        // Toggle OFF
        $response2 = $this->actingAs($user)->post(route('user.bookmarks.toggle', $project));
        $response2->assertRedirect();
        $this->assertFalse($project->isBookmarkedBy($user));
    }
}
