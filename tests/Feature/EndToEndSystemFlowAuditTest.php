<?php

namespace Tests\Feature;

use App\Enums\FileType;
use App\Enums\ProjectStatus;
use App\Enums\TaskPhase;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\RecoveryTask;
use App\Models\Technology;
use App\Models\User;
use App\Services\ProjectService;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class EndToEndSystemFlowAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_complete_end_to_end_platform_lifecycle(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        // 1. PUBLIC GUEST FLOW
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('explore.index'))->assertStatus(200);
        $this->get(route('resurrected.index'))->assertStatus(200);
        $this->get(route('about'))->assertStatus(200);

        // 2. USER REGISTRATION & ADMIN APPROVAL
        $regRes = $this->post(route('register.post'), [
            'name' => 'Original Author',
            'username' => 'author1',
            'email' => 'author@afterlife.dev',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'github_username' => 'author1',
            'bio' => 'Original developer.',
            'terms' => '1',
        ]);
        $regRes->assertRedirect(route('register.pending', ['email' => 'author@afterlife.dev']));

        $author = User::where('email', 'author@afterlife.dev')->firstOrFail();
        $this->assertEquals(UserStatus::PENDING, $author->status);

        // Admin approves author
        $admin = User::factory()->create(['role' => UserRole::ADMIN, 'status' => UserStatus::ACTIVE]);
        $author->update(['status' => UserStatus::ACTIVE]);

        // Register adopter
        $adopter = User::factory()->create([
            'name' => 'New Maintainer',
            'username' => 'adopter1',
            'email' => 'adopter@afterlife.dev',
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        // 3. PROJECT CREATION BY AUTHOR
        $category = Category::firstOrFail();
        $tech = Technology::firstOrFail();

        // Create sample valid zip
        $tempZip = (string) tempnam(sys_get_temp_dir(), 'e2e_zip_');
        $zip = new ZipArchive();
        $zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('README.md', '# E2E Project\nFull test repository.');
        $zip->addFromString('src/main.js', 'console.log("alive");');
        $zip->close();

        $uploadedZip = new UploadedFile($tempZip, 'source.zip', 'application/zip', null, true);

        $projectService = app(ProjectService::class);
        $project = $projectService->createProject([
            'title' => 'Abandoned Game Engine',
            'short_description' => 'A lightweight game engine for indie games.',
            'description' => 'A full 2D game engine built in C++ and JavaScript that was abandoned by the original studio.',
            'category_id' => $category->id,
            'reason_for_abandonment' => 'Original team moved to new projects and can no longer maintain.',
            'ownership_confirmed' => true,
        ], $author, ['source_zip' => $uploadedZip]);
        @unlink($tempZip);

        $this->assertEquals(ProjectStatus::PENDING_REVIEW, $project->status);
        $this->assertEquals($author->id, $project->original_owner_id);

        // 4. ADMIN REVIEW & APPROVAL
        $this->actingAs($admin);
        $approveRes = $this->post(route('admin.projects.submissions.approve', $project), [
            'admin_notes' => 'Codebase inspected and verified safe.',
        ]);
        $approveRes->assertRedirect();
        $project->refresh();
        $this->assertEquals(ProjectStatus::AVAILABLE, $project->status);

        // 5. PUBLIC EXPLORER & FILE PREVIEW
        $this->actingAs($adopter);
        $detailsRes = $this->get(route('explore.show', $project));
        $detailsRes->assertStatus(200);

        $zipFile = $project->files->firstWhere('file_type', FileType::SOURCE_CODE_ZIP);
        $this->assertNotNull($zipFile);

        $previewRes = $this->getJson(route('explore.files.preview', [
            'project' => $project,
            'file' => $zipFile,
            'path' => 'README.md',
        ]));
        $previewRes->assertStatus(200);
        $this->assertStringContainsString('E2E Project', (string) $previewRes->json('content'));

        $certRes = $this->get(route('explore.certificate', $project));
        $certRes->assertStatus(200);

        // 6. ADOPTION PROPOSAL
        $adoptRes = $this->post(route('user.adoptions.store', $project), [
            'reason' => 'I have 5 years experience maintaining 2D game engines and open source projects.',
            'proposed_improvements' => 'Upgrade to WebGL 2.0 and fix memory leaks across all core rendering loops.',
            'recovery_plan' => 'Phase 1: Setup CI/CD\nPhase 2: Fix failing test cases\nPhase 3: Tag v1.0.0 stable release.',
            'expected_completion_date' => now()->addMonths(2)->format('Y-m-d'),
            'relevant_skills' => 'C++, JavaScript, WebGL, Docker, Automated Testing',
        ]);
        $project->refresh();
        $this->assertEquals(ProjectStatus::ADOPTION_PENDING, $project->status);
        $adoptionRequest = $project->adoptionRequests()->latest()->firstOrFail();

        // 7. ADMIN APPROVAL & ATOMIC OWNERSHIP TRANSFER
        $this->actingAs($admin);
        $transferRes = $this->post(route('admin.adoption-requests.approve', $adoptionRequest), [
            'admin_notes' => 'Strong developer profile and clear roadmap.',
        ]);
        $transferRes->assertRedirect();
        $project->refresh();

        $this->assertEquals(ProjectStatus::UNDER_RECOVERY, $project->status);
        $this->assertEquals($adopter->id, $project->owner_id);
        $this->assertEquals($author->id, $project->original_owner_id);
        $this->assertDatabaseHas('ownership_transfers', [
            'project_id' => $project->id,
            'previous_owner_id' => $author->id,
            'new_owner_id' => $adopter->id,
            'approved_by' => $admin->id,
        ]);

        // 8. RECOVERY WORKSPACE COLLABORATION
        $this->actingAs($adopter);
        $wsRes = $this->get(route('user.recovery.workspace', $project));
        $wsRes->assertStatus(200);

        // Create recovery task
        $taskRes = $this->post(route('user.recovery.tasks.store', $project), [
            'title' => 'Update build pipeline to Webpack 5',
            'description' => 'Fix legacy build issues and outdated dependencies.',
            'phase' => TaskPhase::REPAIR->value,
            'priority' => TaskPriority::HIGH->value,
        ]);
        $taskRes->assertRedirect();
        $task = RecoveryTask::where('project_id', $project->id)->firstOrFail();

        // Update task status
        $toggleRes = $this->patch(route('user.recovery.tasks.update', [$project, $task]), [
            'status' => 'completed',
        ]);
        $toggleRes->assertRedirect();
        $task->refresh();
        $this->assertEquals(TaskStatus::COMPLETED, $task->status);

        // Post workspace discussion note
        $commentRes = $this->post(route('user.recovery.comments.store', $project), [
            'comment' => 'Build pipeline successfully modernized and passing.',
        ]);
        $commentRes->assertRedirect();

        // Tag version release
        $versionRes = $this->post(route('user.versions.store', $project), [
            'version_number' => 'v1.0.0',
            'release_notes' => 'Initial resurrected release with all core features restored.',
        ]);
        $versionRes->assertRedirect();

        // Submit final resurrection review
        $finalRes = $this->post(route('user.final-review.store', $project), [
            'completion_summary' => 'The engine is 100% recovered with modern graphics support, complete documentation, and fully passing unit tests.',
            'completed_features' => 'Modern WebGL 2.0 rendering engine integration with full backward compatibility and automated CI/CD pipelines.',
            'testing_summary' => 'All unit and integration regression test suites pass with 100% code coverage across all core modules.',
        ]);
        $finalRes->assertRedirect();
        $project->refresh();
        $this->assertEquals(ProjectStatus::PENDING_FINAL_REVIEW, $project->status);

        // 9. ADMIN CERTIFIES RESURRECTION
        $this->actingAs($admin);
        $finalReview = $project->latestFinalReview;
        $this->assertNotNull($finalReview);

        $certifyRes = $this->post(route('admin.final-reviews.approve', $finalReview), [
            'admin_feedback' => 'Exceptional recovery and documentation. Certified.',
        ]);
        $certifyRes->assertRedirect();
        $project->refresh();

        $this->assertEquals(ProjectStatus::RESURRECTED, $project->status);
        $this->assertNotNull($project->resurrected_at);

        // 10. PUBLIC HALL OF FAME VERIFICATION
        $hallRes = $this->get(route('resurrected.index'));
        $hallRes->assertStatus(200);
        $hallRes->assertSee('Abandoned Game Engine');
        $hallRes->assertSee('New Maintainer');
        $hallRes->assertSee('Original Author');

        // 11. INACTIVITY MONITOR COMMAND CHECK
        $this->artisan('afterlife:check-inactivity')->assertSuccessful();
    }
}
