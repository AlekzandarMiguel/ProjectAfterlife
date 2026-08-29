<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master System Administrator
        $admin = User::updateOrCreate(
            ['email' => 'admin@afterlife.dev'],
            [
                'name' => 'Alexander Vance',
                'username' => 'alex_admin',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'status' => UserStatus::ACTIVE,
                'github_url' => 'https://github.com/alex-vance',
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'bio' => 'Lead Systems Architect and Platform Administrator at Project Afterlife. Passionate about reviving stalled open-source software.',
                'years_of_experience' => 12,
                'website_url' => 'https://afterlife.dev',
                'location' => 'San Francisco, CA',
                'skills' => ['Laravel', 'PHP', 'Security', 'MySQL', 'System Architecture', 'DevOps'],
            ]
        );

        // 2. Realistic User Accounts
        $users = [
            [
                'name' => 'Elena Rostova',
                'username' => 'elena_code',
                'email' => 'elena@afterlife.dev',
                'bio' => 'Senior Backend Developer specializing in Go and high-throughput microservices. Former startup founder.',
                'exp' => 7,
                'skills' => ['Go', 'PostgreSQL', 'Docker', 'Redis', 'Microservices'],
                'location' => 'Berlin, Germany',
            ],
            [
                'name' => 'Marcus Chen',
                'username' => 'marcus_dev',
                'email' => 'marcus@afterlife.dev',
                'bio' => 'Full-stack TypeScript and Python engineer. Love picking up abandoned CLI tools and libraries.',
                'exp' => 5,
                'skills' => ['TypeScript', 'Node.js', 'Python', 'React', 'FastAPI'],
                'location' => 'Singapore',
            ],
            [
                'name' => 'Sarah Jenkins',
                'username' => 'sjenkins',
                'email' => 'sarah@afterlife.dev',
                'bio' => 'Mobile application architect (Flutter & Kotlin). Passionate about restoring abandoned native apps.',
                'exp' => 6,
                'skills' => ['Flutter', 'Dart', 'Android', 'Firebase', 'SQLite'],
                'location' => 'Toronto, Canada',
            ],
            [
                'name' => 'Devon O\'Connor',
                'username' => 'devon_oc',
                'email' => 'devon@afterlife.dev',
                'bio' => 'PHP & Laravel specialist. Author of multiple open-source packages.',
                'exp' => 9,
                'skills' => ['PHP', 'Laravel', 'MySQL', 'Tailwind CSS', 'Vue.js'],
                'location' => 'Dublin, Ireland',
            ],
            [
                'name' => 'Kaito Tanaka',
                'username' => 'kaito_t',
                'email' => 'kaito@afterlife.dev',
                'bio' => 'Systems engineer passionate about Rust and WebAssembly.',
                'exp' => 4,
                'skills' => ['Rust', 'WebAssembly', 'C#', 'Docker'],
                'location' => 'Tokyo, Japan',
            ],
            [
                'name' => 'Amira Al-Mansoor',
                'username' => 'amira_m',
                'email' => 'amira@afterlife.dev',
                'bio' => 'Security researcher and full-stack web developer. Focuses on code refactoring and modernizing legacy stacks.',
                'exp' => 8,
                'skills' => ['Python', 'Django', 'Security', 'PostgreSQL', 'React'],
                'location' => 'Dubai, UAE',
            ],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'username' => $u['username'],
                    'password' => Hash::make('password'),
                    'role' => UserRole::USER,
                    'status' => UserStatus::ACTIVE,
                    'github_url' => 'https://github.com/' . $u['username'],
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $u['bio'],
                    'years_of_experience' => $u['exp'],
                    'location' => $u['location'],
                    'skills' => $u['skills'],
                ]
            );
        }
    }
}
