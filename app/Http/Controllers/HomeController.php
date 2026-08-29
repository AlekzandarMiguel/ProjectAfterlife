<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\OwnershipTransfer;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_projects' => Project::count(),
            'available_projects' => Project::where('status', ProjectStatus::AVAILABLE)->count(),
            'active_recoveries' => Project::whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY])->count(),
            'resurrected_projects' => Project::where('status', ProjectStatus::RESURRECTED)->count(),
            'total_developers' => User::where('role', 'user')->count(),
            'ownership_transfers' => OwnershipTransfer::count(),
        ];

        $featuredProjects = Project::with(['category', 'technologies', 'owner', 'originalOwner'])
            ->whereIn('status', [ProjectStatus::AVAILABLE, ProjectStatus::UNDER_RECOVERY, ProjectStatus::RESURRECTED])
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $latestResurrected = Project::with(['category', 'technologies', 'owner', 'originalOwner', 'latestVersion'])
            ->where('status', ProjectStatus::RESURRECTED)
            ->latest('resurrected_at')
            ->take(3)
            ->get();

        $categories = Category::withCount(['projects' => function ($q) {
            $q->whereIn('status', [ProjectStatus::AVAILABLE, ProjectStatus::UNDER_RECOVERY, ProjectStatus::RESURRECTED]);
        }])->get();

        return view('public.home', compact('stats', 'featuredProjects', 'latestResurrected', 'categories'));
    }

    public function about(): View
    {
        return view('public.about');
    }
}
