<?php

namespace Tests\Feature;

use App\Mail\AccountApprovedMail;
use App\Mail\PasswordResetOtpMail;
use App\Mail\RegistrationReceivedMail;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    public function test_registration_creates_pending_account_and_requires_admin_approval(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        // 1. User registers
        $response = $this->post(route('register.post'), [
            'name' => 'Sarah Connor',
            'username' => 'sarahc',
            'email' => 'sarah@afterlife.dev',
            'password' => 'Pass1234',
            'password_confirmation' => 'Pass1234',
            'terms' => '1',
        ]);

        // Redirects to pending screen
        $response->assertRedirect(route('register.pending', ['email' => 'sarah@afterlife.dev']));

        // Stored with PENDING status
        $user = User::where('email', 'sarah@afterlife.dev')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserStatus::PENDING, $user->status);

        // Verify registration email sent to user
        Mail::assertSent(RegistrationReceivedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // 2. Attempting to log in while pending fails
        $loginAttempt = $this->post(route('login.post'), [
            'email' => 'sarah@afterlife.dev',
            'password' => 'Pass1234',
        ]);
        $loginAttempt->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // 3. Admin approves account
        $this->actingAs($admin)->post(route('admin.users.approve', $user));

        $user->refresh();
        $this->assertEquals(UserStatus::ACTIVE, $user->status);

        // Verify account approved email sent to user
        Mail::assertSent(AccountApprovedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // 4. User can now successfully sign in (with clean guest state)
        \Illuminate\Support\Facades\Auth::logout();
        $this->flushSession();
        $this->post(route('login.post'), [
            'email' => 'sarah@afterlife.dev',
            'password' => 'Pass1234',
        ])->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_forgot_password_and_password_reset_flow(): void
    {
        $this->post(route('logout'));
        $this->flushSession();
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'resurrector@afterlife.dev',
            'password' => Hash::make('OldPassword1'),
            'status' => UserStatus::ACTIVE,
        ]);

        // 1. Request 6-digit OTP
        $res = $this->post(route('password.email'), [
            'email' => 'resurrector@afterlife.dev',
        ]);
        $res->assertRedirect(route('password.verify.form', ['email' => 'resurrector@afterlife.dev']));

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'resurrector@afterlife.dev',
        ]);

        // Verify OTP email sent
        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && strlen($mail->otp) === 6;
        });

        // 2. Test Invalid Code fails
        $failVerify = $this->post(route('password.verify.code'), [
            'email' => 'resurrector@afterlife.dev',
            'code' => '000000',
        ]);
        $failVerify->assertSessionHasErrors(['code']);

        // Mock known 6-digit OTP
        $knownOtp = '749201';
        DB::table('password_reset_tokens')->where('email', 'resurrector@afterlife.dev')->update([
            'token' => Hash::make($knownOtp),
            'created_at' => now(),
        ]);

        // 3. Verify valid 6-digit code
        $verifyRes = $this->post(route('password.verify.code'), [
            'email' => 'resurrector@afterlife.dev',
            'code' => $knownOtp,
        ]);

        $verifyRes->assertSessionHasNoErrors();
        $this->assertTrue($verifyRes->isRedirect());

        // Get the generated authorization token from redirect URL path (/reset-password/{token})
        $redirectUrl = $verifyRes->headers->get('Location');
        $path = parse_url($redirectUrl, PHP_URL_PATH);
        $verifiedToken = basename($path);
        $this->assertNotEmpty($verifiedToken);

        // 4. Set new password
        $resetRes = $this->post(route('password.update'), [
            'token' => $verifiedToken,
            'email' => 'resurrector@afterlife.dev',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $resetRes->assertRedirect(route('login'));
        $resetRes->assertSessionHas('success');

        // Verify new password works
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123', $user->password));

        // Token is consumed & deleted
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'resurrector@afterlife.dev',
        ]);
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
    public function test_admin_can_provision_new_users_and_admins(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        // 1. Admin creates a new Administrator account
        $resAdmin = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Secondary Admin',
            'username' => 'secadmin',
            'email' => 'secadmin@afterlife.dev',
            'password' => 'SecAdminPass123',
            'password_confirmation' => 'SecAdminPass123',
            'role' => 'admin',
            'status' => 'active',
            'bio' => 'Security Operations Lead',
        ]);

        $resAdmin->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'secadmin@afterlife.dev',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Admin creates a new Developer account
        $resUser = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Provisioned Dev',
            'username' => 'provdev',
            'email' => 'provdev@afterlife.dev',
            'password' => 'ProvDevPass123',
            'password_confirmation' => 'ProvDevPass123',
            'role' => 'user',
            'status' => 'active',
            'bio' => 'Legacy PHP specialist',
        ]);

        $resUser->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'provdev@afterlife.dev',
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_regular_user_cannot_provision_accounts(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'Hacker Admin',
            'username' => 'hackeradmin',
            'email' => 'hacker@afterlife.dev',
            'password' => 'HackerPass123',
            'password_confirmation' => 'HackerPass123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response->assertStatus(403);
    }
    public function test_redirect_to_google_oauth(): void
    {
        $response = $this->get(route('auth.google'));
        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location') ?? '');
    }

    public function test_google_callback_creates_pending_developer_account(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-unique-id-12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('googledev@afterlife.dev');
        $abstractUser->shouldReceive('getName')->andReturn('Google Developer');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/a/test-avatar');

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('register.pending', ['email' => 'googledev@afterlife.dev']));

        $user = User::where('email', 'googledev@afterlife.dev')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserStatus::PENDING, $user->status);
        $this->assertEquals('google-unique-id-12345', $user->google_id);
        $this->assertEquals('google', $user->auth_provider);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\RegistrationReceivedMail::class);
    }

    public function test_google_callback_logs_in_existing_active_user(): void
    {
        $user = User::factory()->create([
            'email' => 'activegoogle@afterlife.dev',
            'google_id' => 'existing-google-id-999',
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('existing-google-id-999');
        $abstractUser->shouldReceive('getEmail')->andReturn('activegoogle@afterlife.dev');
        $abstractUser->shouldReceive('getName')->andReturn('Active Google User');
        $abstractUser->shouldReceive('getAvatar')->andReturn(null);

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
    public function test_admin_can_securely_promote_user_to_admin_with_password_verification(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'password' => bcrypt('AdminSecret123'),
        ]);

        $developer = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.promote', $developer), [
            'role' => 'admin',
            'reason' => 'Demonstrated exceptional contribution and approved for platform governance.',
            'admin_password' => 'AdminSecret123',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $developer->refresh();
        $this->assertEquals(UserRole::ADMIN, $developer->role);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'ADMIN_USER_ROLE_ELEVATION',
            'entity_id' => $developer->id,
        ]);
    }

    public function test_promotion_fails_with_incorrect_admin_password(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'password' => bcrypt('CorrectPassword123'),
        ]);

        $developer = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.promote', $developer), [
            'role' => 'admin',
            'reason' => 'Security elevation attempt.',
            'admin_password' => 'WrongPassword999',
        ]);

        $response->assertSessionHasErrors(['admin_password']);

        $developer->refresh();
        $this->assertEquals(UserRole::USER, $developer->role);
    }
    public function test_admin_can_access_and_manage_notifications_hub(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        \App\Services\NotificationService::notifyAdmins(
            'system_test_alert',
            'Test System Alert',
            'A test alert for administrator governance.',
            route('admin.dashboard')
        );

        $response = $this->actingAs($admin)->get(route('admin.notifications.index'));
        $response->assertOk();
        $response->assertSee('Test System Alert');

        // Mark all as read
        $readAllRes = $this->actingAs($admin)->post(route('admin.notifications.read-all'));
        $readAllRes->assertRedirect();

        $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('notifiable_id', $admin->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(0, $unreadCount);
    }
}
