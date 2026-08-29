<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_custom_profile_picture(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Alexander Miguel Pallasigue',
            'username' => 'inzaghi',
            'email' => 'inzaghi@afterlife.dev',
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
            'avatar' => null,
        ]);

        $this->actingAs($user);

        // Upload custom avatar
        $avatarFile = UploadedFile::fake()->image('custom_avatar.png', 300, 300);

        $response = $this->put(route('user.profile.update'), [
            'name' => 'Alexander Miguel Pallasigue',
            'username' => 'inzaghi',
            'email' => 'inzaghi@afterlife.dev',
            'avatar' => $avatarFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
        $this->assertStringContainsString('avatars/', (string) $user->avatar);
        $this->assertStringContainsString('/storage/avatars/', $user->avatar_url);
    }

    public function test_user_can_remove_custom_profile_picture(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Alexander Miguel',
            'username' => 'alex',
            'email' => 'alex@afterlife.dev',
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
            'avatar' => 'avatars/sample_pic.png',
        ]);

        // Put a fake file on disk
        Storage::disk('public')->put('avatars/sample_pic.png', 'test-image-content');

        $this->actingAs($user);

        $response = $this->put(route('user.profile.update'), [
            'name' => 'Alexander Miguel',
            'username' => 'alex',
            'email' => 'alex@afterlife.dev',
            'remove_avatar' => '1',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertNull($user->avatar);
        Storage::disk('public')->assertMissing('avatars/sample_pic.png');
    }

    public function test_user_initials_accessor_calculates_correctly(): void
    {
        $user1 = User::factory()->make(['name' => 'Alexander Miguel Pallasigue']);
        $this->assertEquals('AM', $user1->initials);

        $user2 = User::factory()->make(['name' => 'SingleName']);
        $this->assertEquals('S', $user2->initials);
    }
}
