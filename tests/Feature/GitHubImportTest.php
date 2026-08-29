<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Services\GitHubImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
    }

    public function test_github_import_returns_parsed_metadata(): void
    {
        Http::fake([
            'api.github.com/repos/facebook/react' => Http::response([
                'name' => 'react',
                'description' => 'The library for web and native user interfaces.',
                'html_url' => 'https://github.com/facebook/react',
                'language' => 'JavaScript',
                'license' => ['spdx_id' => 'MIT', 'name' => 'MIT License'],
                'stargazers_count' => 220000,
                'forks_count' => 45000,
                'open_issues_count' => 1200,
                'archived' => false,
                'pushed_at' => '2026-08-20T10:00:00Z',
            ], 200),
        ]);

        $this->actingAs($this->user)
            ->postJson(route('user.projects.import-github'), [
                'url' => 'https://github.com/facebook/react',
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'react',
                    'short_description' => 'The library for web and native user interfaces.',
                    'license' => 'MIT',
                ],
            ]);
    }

    public function test_github_import_handles_invalid_url(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('user.projects.import-github'), [
                'url' => 'not-a-valid-url',
            ])
            ->assertStatus(422);
    }
}
