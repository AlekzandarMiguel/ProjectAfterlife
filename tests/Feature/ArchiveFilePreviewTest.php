<?php

namespace Tests\Feature;

use App\Enums\FileType;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class ArchiveFilePreviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_public_user_can_preview_text_file_inside_zip_archive(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => UserRole::USER]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Demo Project',
            'slug' => 'demo-project',
            'short_description' => 'A demo repo',
            'description' => 'Longer description here',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::AVAILABLE,
            'reason_for_abandonment' => 'No time',
        ]);

        // Create dummy zip with README.md
        $tempZip = (string) tempnam(sys_get_temp_dir(), 'test_zip_');
        $zip = new ZipArchive();
        $zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('README.md', '# Project Afterlife Demo\nThis is the markdown preview content.');
        $zip->close();

        $storagePath = 'projects/' . $project->id . '/test.zip';
        Storage::disk('local')->put($storagePath, (string) file_get_contents($tempZip));
        @unlink($tempZip);

        $projectFile = ProjectFile::create([
            'project_id' => $project->id,
            'uploaded_by' => $user->id,
            'file_name' => 'test.zip',
            'storage_path' => $storagePath,
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 1024,
            'mime_type' => 'application/zip',
            'is_scanned' => true,
            'security_status' => 'clean',
        ]);

        $response = $this->getJson(route('explore.files.preview', [
            'project' => $project,
            'file' => $projectFile,
            'path' => 'README.md',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'filename' => 'README.md',
        ]);
        $this->assertStringContainsString('Project Afterlife Demo', (string) $response->json('content'));
    }

    public function test_file_preview_rejects_path_traversal_attempts(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => UserRole::USER]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Demo Project 2',
            'slug' => 'demo-project-2',
            'short_description' => 'A demo repo',
            'description' => 'Longer description here',
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'original_owner_id' => $user->id,
            'status' => ProjectStatus::AVAILABLE,
            'reason_for_abandonment' => 'No time',
        ]);

        $projectFile = ProjectFile::create([
            'project_id' => $project->id,
            'uploaded_by' => $user->id,
            'file_name' => 'dummy.zip',
            'storage_path' => 'projects/' . $project->id . '/dummy.zip',
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 1024,
            'mime_type' => 'application/zip',
            'is_scanned' => true,
            'security_status' => 'clean',
        ]);

        $response = $this->getJson(route('explore.files.preview', [
            'project' => $project,
            'file' => $projectFile,
            'path' => '../secret.txt',
        ]));

        $response->assertStatus(400);
    }
}
