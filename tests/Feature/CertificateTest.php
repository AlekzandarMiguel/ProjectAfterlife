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
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_public_user_can_view_software_provenance_certificate(): void
    {
        $author = User::factory()->create(['name' => 'Original Author', 'role' => UserRole::USER]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Preserved Engine',
            'slug' => 'preserved-engine',
            'short_description' => 'A preserved core engine',
            'description' => 'Full description of engine',
            'category_id' => $category->id,
            'owner_id' => $author->id,
            'original_owner_id' => $author->id,
            'status' => ProjectStatus::AVAILABLE,
            'license_type' => 'MIT License',
            'reason_for_abandonment' => 'Archival',
        ]);

        ProjectFile::create([
            'project_id' => $project->id,
            'uploaded_by' => $author->id,
            'file_name' => 'archive.zip',
            'storage_path' => 'projects/' . $project->id . '/archive.zip',
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 2048,
            'mime_type' => 'application/zip',
            'sha256_hash' => 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855',
            'is_scanned' => true,
            'security_status' => 'clean',
        ]);

        $response = $this->get(route('explore.certificate', $project));

        $response->assertStatus(200);
        $response->assertSee('Certificate of Software Provenance');
        $response->assertSee('Preserved Engine');
        $response->assertSee('Original Author');
        $response->assertSee('e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
    }
}
