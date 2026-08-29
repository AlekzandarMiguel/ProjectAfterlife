<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['category', 'owner', 'originalOwner', 'technologies']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $projects = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        $statuses = ProjectStatus::cases();

        return view('admin.projects.all', compact('projects', 'categories', 'statuses'));
    }

    public function toggleFeatured(Project $project): RedirectResponse
    {
        $project->update(['is_featured' => !$project->is_featured]);
        AuditService::log('PROJECT_FEATURED_TOGGLED', $project, ['is_featured' => $project->is_featured]);

        return back()->with('success', "Project feature flag updated to " . ($project->is_featured ? 'Featured' : 'Standard') . '.');
    }
}
