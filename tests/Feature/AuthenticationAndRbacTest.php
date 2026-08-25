<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationAndRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TechnologySeeder::class);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'developer@afterlife.dev',
            'password' => bcrypt('password'),
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'developer@afterlife.dev',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_when_fields_are_missing_or_invalid(): void
    {
        // 1. Missing fields
        $res1 = $this->post(route('login.post'), [
            'email' => '',
            'password' => '',
        ]);
        $res1->assertSessionHasErrors(['email', 'password']);

        // 2. Invalid email format
        $res2 = $this->post(route('login.post'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);
        $res2->assertSessionHasErrors(['email']);
    }

    public function test_registration_fails_when_inputs_are_missing_or_invalid(): void
    {
        // 1. All empty
        $res1 = $this->post(route('register.post'), []);
        $res1->assertSessionHasErrors(['name', 'username', 'email', 'password', 'terms']);

        // 2. Junk / invalid formats (short name, invalid chars, bad email, weak password)
        $res2 = $this->post(route('register.post'), [
            'name' => '12', // too short and numbers
            'username' => 'ab', // too short
            'email' => 'random_junk_email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
            'terms' => '0',
        ]);
        $res2->assertSessionHasErrors(['name', 'username', 'email', 'password', 'terms']);
    }

    public function test_registration_succeeds_with_valid_data(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Sarah Connor',
            'username' => 'sarahc',
            'email' => 'sarah@afterlife.dev',
            'password' => 'Pass1234',
            'password_confirmation' => 'Pass1234',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'sarah@afterlife.dev',
            'username' => 'sarahc',
        ]);
    }

    public function test_forgot_password_and_password_reset_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'resurrector@afterlife.dev',
            'password' => Hash::make('OldPassword1'),
        ]);

        // Request reset link
        $res = $this->post(route('password.email'), [
            'email' => 'resurrector@afterlife.dev',
        ]);
        $res->assertSessionHas('status');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'resurrector@afterlife.dev',
        ]);

        // Reset password
        $token = 'test-token-12345';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'resurrector@afterlife.dev'],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetRes = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'resurrector@afterlife.dev',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $resetRes->assertRedirect(route('login'));
        $resetRes->assertSessionHas('success');

        // Verify new password works
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
    }

    public function test_regular_user_cannot_access_admin_console(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_console(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('System Control Center');
    }

    public function test_guest_is_redirected_to_login_from_user_routes(): void
    {
        $response = $this->get(route('user.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
