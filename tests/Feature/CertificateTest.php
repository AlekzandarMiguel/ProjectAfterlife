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

    public function test_original_owner_and_adopter_and_admin_can_view_certificate(): void
    {
        $author = User::factory()->create(['name' => 'Original Author', 'role' => UserRole::USER]);
        $adopter = User::factory()->create(['name' => 'Adopter Maintainer', 'role' => UserRole::USER]);
        $admin = User::factory()->create(['name' => 'Platform Admin', 'role' => UserRole::ADMIN]);
        $unrelatedUser = User::factory()->create(['name' => 'Unrelated User', 'role' => UserRole::USER]);
        $category = Category::firstOrFail();

        $project = Project::create([
            'title' => 'Preserved Engine',
            'slug' => 'preserved-engine',
            'short_description' => 'A preserved core engine',
            'description' => 'Full description of engine',
            'category_id' => $category->id,
            'owner_id' => $adopter->id,
            'original_owner_id' => $author->id,
            'status' => ProjectStatus::RESURRECTED,
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

        // 1. Guest is redirected to login
        $guestResponse = $this->get(route('explore.certificate', $project));
        $guestResponse->assertRedirect(route('login'));

        // 2. Unrelated user gets 403 Forbidden
        $this->actingAs($unrelatedUser);
        $unrelatedResponse = $this->get(route('explore.certificate', $project));
        $unrelatedResponse->assertStatus(403);

        // 3. Original Author gets 200 OK
        $this->actingAs($author);
        $authorResponse = $this->get(route('explore.certificate', $project));
        $authorResponse->assertStatus(200);
        $authorResponse->assertSee('Software Provenance');
        $authorResponse->assertSee('VERIFIED');

        // 4. Adopter / Current Owner gets 200 OK
        $this->actingAs($adopter);
        $adopterResponse = $this->get(route('explore.certificate', $project));
        $adopterResponse->assertStatus(200);
        $adopterResponse->assertSee('AUTHORIZED');

        // 5. Admin gets 200 OK
        $this->actingAs($admin);
        $adminResponse = $this->get(route('explore.certificate', $project));
        $adminResponse->assertStatus(200);
    }
}
