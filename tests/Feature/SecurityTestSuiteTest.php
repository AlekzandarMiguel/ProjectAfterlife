<?php

namespace Tests\Feature;

use App\Enums\AdoptionStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\AdoptionRequest;
use App\Models\Category;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTestSuiteTest extends TestCase
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
    }

    /** 1. Authentication Security */
    public function test_unauthenticated_guests_cannot_access_user_or_admin_endpoints(): void
    {
        $this->get(route('user.dashboard'))->assertRedirect(route('login'));
        $this->get(route('user.projects.create'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    /** 2. RBAC & Privilege Separation */
    public function test_regular_user_cannot_access_admin_moderation_or_settings(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($user)->get(route('admin.projects.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('admin.users.index'))->assertStatus(403);
    }

    /** 3. IDOR Prevention (Insecure Direct Object Reference) */
    public function test_user_cannot_manage_or_edit_another_users_project(): void
    {
        $owner = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $attacker = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        $project = $this->projectService->createProject([
            'title' => 'Secure Engine Project',
            'short_description' => 'Testing IDOR protection',
            'description' => 'Comprehensive detailed test description for security validation.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Lack of time to maintain.',
            'ownership_confirmed' => true,
        ], $owner);

        // Attacker attempts to access edit view or recovery workspace
        $this->actingAs($attacker)->get(route('user.projects.edit', $project))->assertStatus(403);
        $this->actingAs($attacker)->get(route('user.recovery.workspace', $project))->assertStatus(403);
    }

    /** 4. Role Escalation Protection */
    public function test_user_cannot_escalate_role_or_tamper_with_user_profiles(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $targetAdmin = User::factory()->create(['role' => UserRole::ADMIN, 'status' => UserStatus::ACTIVE]);

        // Attempting to patch admin user management
        $this->actingAs($user)->patch(route('admin.users.toggle-status', $targetAdmin), [
            'status' => 'suspended',
        ])->assertStatus(403);
    }

    /** 5. Adoption Flow Protection */
    public function test_user_cannot_adopt_their_own_project(): void
    {
        $owner = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);

        $project = $this->projectService->createProject([
            'title' => 'Self Adoption Test',
            'short_description' => 'Cannot adopt own project',
            'description' => 'Valid test project description with sufficient length for validation.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Maintenance transferred.',
            'ownership_confirmed' => true,
        ], $owner);

        $project->update(['status' => ProjectStatus::AVAILABLE]);

        $this->actingAs($owner)->get(route('user.adoptions.create', $project))->assertRedirect(route('explore.show', $project));
    }

    /** 6. Atomic Ownership Transfer Security */
    public function test_only_admin_can_approve_adoption_and_trigger_atomic_transfer(): void
    {
        $owner = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $adopter = User::factory()->create(['role' => UserRole::USER, 'status' => UserStatus::ACTIVE]);
        $admin = User::factory()->create(['role' => UserRole::ADMIN, 'status' => UserStatus::ACTIVE]);

        $project = $this->projectService->createProject([
            'title' => 'Transfer Security Test',
            'short_description' => 'Atomic transfer lock test',
            'description' => 'Valid test project description with sufficient length for validation.',
            'category_id' => $this->category->id,
            'reason_for_abandonment' => 'Original developer left.',
            'ownership_confirmed' => true,
        ], $owner);

        $project->update(['status' => ProjectStatus::AVAILABLE]);

        $adoptionRequest = AdoptionRequest::create([
            'project_id' => $project->id,
            'user_id' => $adopter->id,
            'reason' => 'Legitimate recovery roadmap and engineering commitment with full details.',
            'proposed_improvements' => 'Porting legacy components to PHP 8.2 and modern test suite.',
            'recovery_plan' => 'Phase 1: Code audit, Phase 2: Feature completion.',
            'expected_completion_date' => now()->addDays(30),
            'status' => AdoptionStatus::PENDING,
        ]);

        // Regular user cannot approve adoption
        $this->actingAs($adopter)->post(route('admin.adoption-requests.approve', $adoptionRequest), ['admin_password' => 'password'])->assertStatus(403);

        // Admin fails with wrong password
        $this->actingAs($admin)->from(route('admin.adoption-requests.show', $adoptionRequest))->post(route('admin.adoption-requests.approve', $adoptionRequest), [
            'admin_password' => 'wrong_password',
        ])->assertSessionHasErrors('admin_password');

        // Admin approves adoption with valid password -> atomic transfer occurs
        $this->actingAs($admin)->post(route('admin.adoption-requests.approve', $adoptionRequest), [
            'admin_password' => 'password',
        ])->assertRedirect(route('admin.ownership-transfers.index'));

        $project->refresh();
        $this->assertEquals($adopter->id, $project->owner_id);
        $this->assertEquals($owner->id, $project->original_owner_id);
        $this->assertEquals(ProjectStatus::UNDER_RECOVERY, $project->status);
    }

    /** 7. Tamper-Evident Audit Logging */
    public function test_audit_logs_record_security_sensitive_actions(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
            'email' => 'audit_test@afterlife.dev',
            'password' => bcrypt('password')
        ]);

        $this->post(route('login.post'), [
            'email' => 'audit_test@afterlife.dev',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'USER_LOGIN',
        ]);
    }
}
