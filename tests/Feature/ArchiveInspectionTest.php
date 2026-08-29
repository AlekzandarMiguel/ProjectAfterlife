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
use App\Services\ArchiveInspectionService;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class ArchiveInspectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $creator;
    protected Category $category;
    protected ProjectService $projectService;
    protected ArchiveInspectionService $inspectionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
        $this->category = Category::first() ?? Category::create(['name' => 'General', 'slug' => 'general']);
        $this->creator = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $this->projectService = app(ProjectService::class);
        $this->inspectionService = app(ArchiveInspectionService::class);
    }

    public function test_archive_inspection_generates_file_tree_and_sha256(): void
    {
        // Create temporary real zip file
        $tempZip = tempnam(sys_get_temp_dir(), 'test_zip_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('src/App.php', '<?php echo "Hello World";');
        $zip->addFromString('README.md', '# Project Readme');
        $zip->addFromString('config/database.php', '<?php return [];');
        $zip->close();

        $result = $this->inspectionService->inspect($tempZip);

        $this->assertTrue($result['is_valid']);
        $this->assertEquals('clean', $result['security_status']);
        $this->assertNotEmpty($result['sha256_hash']);
        $this->assertEquals(3, $result['total_files']);
        $this->assertArrayHasKey('src', $result['file_tree']);
        $this->assertArrayHasKey('README.md', $result['file_tree']);

        if (file_exists($tempZip)) {
            unlink($tempZip);
        }
    }

    public function test_archive_inspection_detects_prohibited_executable_files(): void
    {
        $tempZip = tempnam(sys_get_temp_dir(), 'mal_zip_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('malware.exe', 'MZ binary content');
        $zip->addFromString('script.bat', 'del *.*');
        $zip->close();

        $result = $this->inspectionService->inspect($tempZip);

        $this->assertFalse($result['is_valid']);
        $this->assertEquals('suspicious', $result['security_status']);
        $this->assertCount(2, $result['threats']);

        if (file_exists($tempZip)) {
            unlink($tempZip);
        }
    }
}
