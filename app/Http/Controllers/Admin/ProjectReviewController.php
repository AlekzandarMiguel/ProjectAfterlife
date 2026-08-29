<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectReviewController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'PENDING_REVIEW');
        $submissions = Project::with(['category', 'technologies', 'owner', 'originalOwner', 'latestDeclaration'])
            ->where('status', $status)
            ->latest()
            ->paginate(12);

        return view('admin.projects.submissions', compact('submissions', 'status'));
    }

    public function show(Project $project): View
    {
        $project->load([
            'category',
            'technologies',
            'owner.profile',
            'originalOwner.profile',
            'latestDeclaration',
            'versions.files',
            'files',
            'screenshots',
            'history.user',
        ]);

        return view('admin.projects.review', compact('project'));
    }

    public function approve(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->projectService->approveProject($project, auth()->user(), $validated['admin_notes'] ?? null);

        return redirect()->route('admin.projects.submissions.index')
            ->with('success', "Project '{$project->title}' was approved and is now Available for adoption!");
    }

    public function reject(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $this->projectService->rejectProject($project, auth()->user(), $validated['rejection_reason']);

        return redirect()->route('admin.projects.submissions.index')
            ->with('success', "Project '{$project->title}' was rejected.");
    }

    public function requestRevision(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'revision_instructions' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $this->projectService->requestRevision($project, auth()->user(), $validated['revision_instructions']);

        return redirect()->route('admin.projects.submissions.index')
            ->with('success', "Revision requested for project '{$project->title}'.");
    }
}
