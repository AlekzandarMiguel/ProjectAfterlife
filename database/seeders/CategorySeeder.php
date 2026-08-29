<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Application',
                'slug' => 'web-application',
                'description' => 'Full-stack and frontend web applications, portals, and web platforms.',
                'icon' => 'globe',
            ],
            [
                'name' => 'Mobile Applications',
                'slug' => 'mobile-applications',
                'description' => 'iOS, Android, and cross-platform Flutter/React Native mobile applications.',
                'icon' => 'device-phone',
            ],
            [
                'name' => 'Developer Tool & CLI',
                'slug' => 'developer-tool-cli',
                'description' => 'Command line interfaces, compilers, scaffolding utilities, and devtools.',
                'icon' => 'terminal',
            ],
            [
                'name' => 'Backend & API Services',
                'slug' => 'backend-api-services',
                'description' => 'REST APIs, GraphQL microservices, message queues, and headless engines.',
                'icon' => 'server',
            ],
            [
                'name' => 'Data Engineering & Analytics',
                'slug' => 'data-engineering-analytics',
                'description' => 'Data pipelines, ETL processors, scraping systems, and data visualizers.',
                'icon' => 'chart-bar',
            ],
            [
                'name' => 'Security & DevSecOps',
                'slug' => 'security-devsecops',
                'description' => 'Vulnerability scanners, credential auditors, and security monitoring utilities.',
                'icon' => 'shield-check',
            ],
            [
                'name' => 'Libraries & Packages',
                'slug' => 'libraries-packages',
                'description' => 'Reusable libraries, ORMs, UI kits, and framework plugins.',
                'icon' => 'cube',
            ],
            [
                'name' => 'Productivity & SaaS',
                'slug' => 'productivity-saas',
                'description' => 'Task managers, CRM systems, document trackers, and office automation.',
                'icon' => 'briefcase',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
