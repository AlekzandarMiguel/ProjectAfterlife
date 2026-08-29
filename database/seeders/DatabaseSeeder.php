<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TechnologySeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
        ]);

        // Seed Sample Watchlist / Bookmarks
        $elena = User::where('email', 'elena@afterlife.dev')->first();
        $devon = User::where('email', 'devon@afterlife.dev')->first();
        $marcus = User::where('email', 'marcus@afterlife.dev')->first();

        $projects = Project::take(4)->get();
        if ($elena && $projects->count() >= 2) {
            $elena->bookmarkedProjects()->syncWithoutDetaching([$projects[0]->id, $projects[1]->id]);
        }
        if ($devon && $projects->count() >= 3) {
            $devon->bookmarkedProjects()->syncWithoutDetaching([$projects[1]->id, $projects[2]->id]);
        }
        if ($marcus && $projects->count() >= 1) {
            $marcus->bookmarkedProjects()->syncWithoutDetaching([$projects[0]->id]);
        }
    }
}
