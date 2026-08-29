<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GitHubWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_github_webhook_ping_event(): void
    {
        $user = User::factory()->create();
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Webhook API',
            'slug' => 'webhook-api',
            'short_description' => 'API description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'reason_for_abandonment' => 'Legacy architecture.',
        ]);

        $response = $this->postJson(route('webhooks.github', $project), [], [
            'X-GitHub-Event' => 'ping',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Pong! Webhook connected successfully.']);
    }

    public function test_github_webhook_creates_project_version_on_release(): void
    {
        $user = User::factory()->create();
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Syncing Project',
            'slug' => 'syncing-project',
            'short_description' => 'Syncing description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::UNDER_RECOVERY,
            'reason_for_abandonment' => 'Seeking modern maintainer.',
        ]);

        $payload = [
            'release' => [
                'tag_name' => 'v2.1.0',
                'body' => 'Added WebGL 2 rendering pipeline and fixed memory leaks.',
            ],
        ];

        $response = $this->postJson(route('webhooks.github', $project), $payload, [
            'X-GitHub-Event' => 'release',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'version' => 'v2.1.0']);

        $this->assertDatabaseHas('project_versions', [
            'project_id' => $project->id,
            'version_number' => 'v2.1.0',
        ]);
    }
}
