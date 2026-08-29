<?php

namespace Tests\Feature;

use App\Mail\AdoptionStatusMailable;
use App\Mail\InactivityWarningMailable;
use App\Mail\ProjectResurrectedMailable;
use App\Models\AdoptionRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionalEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(TechnologySeeder::class);
    }

    public function test_adoption_status_mailable_renders_properly(): void
    {
        $applicant = User::factory()->create(['name' => 'Jane Developer']);
        $category = Category::firstOrFail();
        $project = Project::create([
            'title' => 'Resurrected Game Engine',
            'slug' => 'resurrected-game-engine',
            'short_description' => 'Engine description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $applicant->id,
            'original_owner_id' => $applicant->id,
            'reason_for_abandonment' => 'Original author moved to new studio.',
        ]);

        $adoptionRequest = AdoptionRequest::create([
            'project_id' => $project->id,
            'user_id' => $applicant->id,
            'reason' => 'Reason',
            'proposed_improvements' => 'Improvements',
            'recovery_plan' => 'Plan',
            'expected_completion_date' => now()->addDays(30),
        ]);

        $mailable = new AdoptionStatusMailable($adoptionRequest, 'approved');
        $mailable->assertHasSubject('Adoption Approved: You are now the steward of Resurrected Game Engine');
        $rendered = $mailable->render();
        $this->assertStringContainsString('Jane Developer', $rendered);
        $this->assertStringContainsString('STEWARDSHIP TRANSFERRED', $rendered);
    }

    public function test_inactivity_warning_mailable_renders_properly(): void
    {
        $owner = User::factory()->create(['name' => 'Alex Maintainer']);
        $category = Category::firstOrFail();
        $project = Project::create([
            'title' => 'Dormant Library',
            'slug' => 'dormant-library',
            'short_description' => 'Library description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'original_owner_id' => $owner->id,
            'reason_for_abandonment' => 'Inactive maintainer.',
        ]);

        $mailable = new InactivityWarningMailable($project, 35);
        $mailable->assertHasSubject('Inactivity Notice: Recovery update required for Dormant Library');
        $rendered = $mailable->render();
        $this->assertStringContainsString('35 days', $rendered);
    }

    public function test_project_resurrected_mailable_renders_properly(): void
    {
        $owner = User::factory()->create(['name' => 'Sam Reviver']);
        $category = Category::firstOrFail();
        $project = Project::create([
            'title' => 'Pioneer Database',
            'slug' => 'pioneer-database',
            'short_description' => 'Database description.',
            'description' => 'Detailed description.',
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'original_owner_id' => $owner->id,
            'reason_for_abandonment' => 'Legacy project completed.',
        ]);

        $mailable = new ProjectResurrectedMailable($project, 'Full unit test suite passing and docker container published.');
        $mailable->assertHasSubject('Official Induction: Pioneer Database is Resurrected into the Hall of Fame');
        $rendered = $mailable->render();
        $this->assertStringContainsString('Sam Reviver', $rendered);
        $this->assertStringContainsString('Hall of Fame', $rendered);
    }
}
