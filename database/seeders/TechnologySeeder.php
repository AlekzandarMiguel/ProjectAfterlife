<?php

namespace Database\Seeders;

use App\Enums\TechType;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            // Languages
            ['name' => 'PHP', 'slug' => 'php', 'type' => TechType::LANGUAGE],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'type' => TechType::LANGUAGE],
            ['name' => 'TypeScript', 'slug' => 'typescript', 'type' => TechType::LANGUAGE],
            ['name' => 'Python', 'slug' => 'python', 'type' => TechType::LANGUAGE],
            ['name' => 'Go', 'slug' => 'go', 'type' => TechType::LANGUAGE],
            ['name' => 'Rust', 'slug' => 'rust', 'type' => TechType::LANGUAGE],
            ['name' => 'Dart', 'slug' => 'dart', 'type' => TechType::LANGUAGE],
            ['name' => 'Ruby', 'slug' => 'ruby', 'type' => TechType::LANGUAGE],
            ['name' => 'Java', 'slug' => 'java', 'type' => TechType::LANGUAGE],
            ['name' => 'C#', 'slug' => 'csharp', 'type' => TechType::LANGUAGE],

            // Frameworks
            ['name' => 'Laravel', 'slug' => 'laravel', 'type' => TechType::FRAMEWORK],
            ['name' => 'React', 'slug' => 'react', 'type' => TechType::FRAMEWORK],
            ['name' => 'Vue.js', 'slug' => 'vue-js', 'type' => TechType::FRAMEWORK],
            ['name' => 'Next.js', 'slug' => 'next-js', 'type' => TechType::FRAMEWORK],
            ['name' => 'Nuxt.js', 'slug' => 'nuxt-js', 'type' => TechType::FRAMEWORK],
            ['name' => 'Flutter', 'slug' => 'flutter', 'type' => TechType::FRAMEWORK],
            ['name' => 'Django', 'slug' => 'django', 'type' => TechType::FRAMEWORK],
            ['name' => 'FastAPI', 'slug' => 'fastapi', 'type' => TechType::FRAMEWORK],
            ['name' => 'Express.js', 'slug' => 'express-js', 'type' => TechType::FRAMEWORK],
            ['name' => 'Spring Boot', 'slug' => 'spring-boot', 'type' => TechType::FRAMEWORK],

            // Databases
            ['name' => 'MySQL', 'slug' => 'mysql', 'type' => TechType::DATABASE],
            ['name' => 'PostgreSQL', 'slug' => 'postgresql', 'type' => TechType::DATABASE],
            ['name' => 'SQLite', 'slug' => 'sqlite', 'type' => TechType::DATABASE],
            ['name' => 'MongoDB', 'slug' => 'mongodb', 'type' => TechType::DATABASE],
            ['name' => 'Redis', 'slug' => 'redis', 'type' => TechType::DATABASE],

            // Frontend
            ['name' => 'Tailwind CSS', 'slug' => 'tailwind-css', 'type' => TechType::FRONTEND],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'type' => TechType::FRONTEND],
            ['name' => 'Alpine.js', 'slug' => 'alpine-js', 'type' => TechType::FRONTEND],

            // Tools
            ['name' => 'Docker', 'slug' => 'docker', 'type' => TechType::TOOL],
            ['name' => 'GraphQL', 'slug' => 'graphql', 'type' => TechType::TOOL],
        ];

        foreach ($technologies as $tech) {
            Technology::updateOrCreate(['slug' => $tech['slug']], $tech);
        }
    }
}
