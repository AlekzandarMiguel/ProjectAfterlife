<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user()->load(['profile', 'ownedProjects', 'uploadedProjects', 'recoveryTasks']);
        return view('user.profile.show', compact('user'));
    }

    public function edit(): View
    {
        $user = auth()->user()->load('profile');
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', "unique:users,username,{$user->id}"],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'skills_input' => ['nullable', 'string'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'github_url' => $validated['github_url'] ?? null,
        ];

        // Handle Avatar Removal
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = null;
        }

        // Handle Custom Avatar Upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http') && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $path;
        }

        $user->update($userData);

        $skillsArray = !empty($validated['skills_input'])
            ? array_map('trim', explode(',', $validated['skills_input']))
            : [];

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $validated['bio'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? 0,
                'website_url' => $validated['website_url'] ?? null,
                'location' => $validated['location'] ?? null,
                'skills' => $skillsArray,
            ]
        );

        AuditService::log('PROFILE_UPDATED', $user);

        return back()->with('success', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditService::log('PASSWORD_CHANGED', auth()->user());

        return back()->with('success', 'Your password has been changed.');
    }
}
