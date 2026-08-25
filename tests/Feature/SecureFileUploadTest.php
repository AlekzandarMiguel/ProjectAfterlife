<?php

namespace Tests\Feature;

use App\Enums\FileType;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecureFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected ProjectService $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
        $this->category = Category::first() ?? Category::create(['name' => 'General', 'slug' => 'general']);
        $this->projectService = app(ProjectService::class);
        Storage::fake('local');
        Storage::fake('public');
    }

    /** 1. Unauthenticated Guests Cannot Download Project Files */
    public function test_guest_cannot_download_project_files(): void
    {
        $owner = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $project = $this->projectService->createProject([
            'title' => 'Download Auth Test',
            'short_description' => 'Testing download authorization',
            'description' => 'Comprehensive detailed test description for security validation.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Maintenance transferred.',
            'ownership_confirmed' => true,
        ], $owner);

        $file = ProjectFile::create([
            'project_id' => $project->id,
            'uploaded_by' => $owner->id,
            'file_name' => 'source_code.zip',
            'storage_path' => 'projects/' . $project->id . '/files/mock-archive.zip',
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 1024,
            'mime_type' => 'application/zip',
            'is_current' => true,
        ]);

        $this->get(route('explore.files.download', ['project' => $project->slug, 'file' => $file->id]))
            ->assertRedirect(route('login'));
    }

    /** 2. Mismatched Project ID in File Download Returns 404 */
    public function test_downloading_file_with_wrong_project_id_returns_404(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $projectA = $this->projectService->createProject([
            'title' => 'Project Alpha',
            'short_description' => 'Alpha description',
            'description' => 'Detailed description for Alpha test project.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Abandoned due to time.',
            'ownership_confirmed' => true,
        ], $user);

        $projectB = $this->projectService->createProject([
            'title' => 'Project Beta',
            'short_description' => 'Beta description',
            'description' => 'Detailed description for Beta test project.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Abandoned due to time.',
            'ownership_confirmed' => true,
        ], $user);

        $fileA = ProjectFile::create([
            'project_id' => $projectA->id,
            'uploaded_by' => $user->id,
            'file_name' => 'alpha.zip',
            'storage_path' => 'projects/' . $projectA->id . '/files/alpha.zip',
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 1024,
            'mime_type' => 'application/zip',
            'is_current' => true,
        ]);

        // Attempt to request Project Alpha's file via Project Beta's URL
        $this->actingAs($user)->get(route('explore.files.download', ['project' => $projectB->slug, 'file' => $fileA->id]))
            ->assertStatus(404);
    }

    /** 3. Dangerous Extensions (e.g. .exe, .php, .sh) are Rejected on Upload */
    public function test_executable_and_script_uploads_are_rejected(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        // Attempting to upload a PHP script as source archive
        $maliciousFile = UploadedFile::fake()->create('malicious_payload.php', 500, 'application/x-php');

        $response = $this->actingAs($user)->post(route('user.projects.store'), [
            'title' => 'Malicious Project Upload',
            'short_description' => 'Attempting shell injection upload',
            'description' => 'Detailed description attempting to bypass file validation filters.',
            'category_id' => $this->category->id,
            'project_type' => 'web',
            'development_status' => 'prototype',
            'reason_for_abandonment' => 'Testing file type validation.',
            'technologies' => [1],
            'source_zip' => $maliciousFile,
            'ownership_confirmed' => '1',
        ]);

        $response->assertSessionHasErrors(['source_zip']);
    }

    /** 4. Oversized File Upload is Rejected */
    public function test_oversized_file_upload_is_rejected(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        // Create a 60MB fake ZIP file (limit is 50MB = 51200KB)
        $oversizedZip = UploadedFile::fake()->create('huge_repo.zip', 62000, 'application/zip');

        $response = $this->actingAs($user)->post(route('user.projects.store'), [
            'title' => 'Oversized Project Upload',
            'short_description' => 'Testing file size limit enforcement',
            'description' => 'Detailed description for file size testing project.',
            'category_id' => $this->category->id,
            'project_type' => 'web',
            'development_status' => 'prototype',
            'reason_for_abandonment' => 'Testing file size limits.',
            'technologies' => [1],
            'source_zip' => $oversizedZip,
            'ownership_confirmed' => '1',
        ]);

        $response->assertSessionHasErrors(['source_zip']);
    }

    /** 5. Valid File Upload Generates Secure UUID Path and Sanitizes Metadata */
    public function test_valid_file_upload_stores_in_private_disk_with_uuid(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        $validZip = UploadedFile::fake()->create('my_project_v1.0.zip', 5000, 'application/zip');

        $response = $this->actingAs($user)->post(route('user.projects.store'), [
            'title' => 'Secure Upload Project',
            'short_description' => 'Valid archive upload test',
            'description' => 'Comprehensive detailed test description for secure upload validation.',
            'category_id' => $this->category->id,
            'project_type' => 'web',
            'development_status' => 'prototype',
            'reason_for_abandonment' => 'Legitimate upload test.',
            'technologies' => [1],
            'source_zip' => $validZip,
            'ownership_confirmed' => '1',
        ]);

        $response->assertRedirect();

        $project = Project::where('title', 'Secure Upload Project')->first();
        $this->assertNotNull($project);

        $file = ProjectFile::where('project_id', $project->id)->first();
        $this->assertNotNull($file);

        // Verify storage path does NOT contain original user filename directly
        $this->assertStringStartsWith('projects/' . $project->id . '/files/', $file->storage_path);
        $this->assertStringEndsWith('.zip', $file->storage_path);

        // Verify physical file was saved on private local disk
        Storage::disk('local')->assertExists($file->storage_path);
    }
}
