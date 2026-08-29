<?php

namespace App\Http\Controllers\User;

use App\Enums\DevelopmentStatus;
use App\Enums\ProjectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectSubmissionController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $technologies = Technology::orderBy('type')->orderBy('name')->get()->groupBy('type.value');
        $projectTypes = ProjectType::cases();
        $devStatuses = DevelopmentStatus::cases();

        return view('user.projects.create', compact('categories', 'technologies', 'projectTypes', 'devStatuses'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $files = [
            'source_zip' => $request->file('source_zip'),
            'readme' => $request->file('readme'),
            'documentation' => $request->file('documentation'),
            'database_sql' => $request->file('database_sql'),
        ];

        $screenshots = $request->file('screenshots', []);

        $project = $this->projectService->createProject(
            $request->validated(),
            auth()->user(),
            $files,
            $screenshots
        );

        return redirect()->route('user.projects.show', $project)
            ->with('success', "Project '{$project->title}' was successfully submitted! It is now pending Administrator verification.");
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $technologies = Technology::orderBy('type')->orderBy('name')->get()->groupBy('type.value');
        $projectTypes = ProjectType::cases();
        $devStatuses = DevelopmentStatus::cases();

        return view('user.projects.edit', compact('project', 'categories', 'technologies', 'projectTypes', 'devStatuses'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'short_description' => ['required', 'string', 'max:350'],
            'description' => ['required', 'string', 'min:50'],
            'category_id' => ['required', 'exists:categories,id'],
            'reason_for_abandonment' => ['required', 'string', 'min:20'],
            'technologies' => ['required', 'array', 'min:1'],
            'technologies.*' => ['exists:technologies,id'],
        ]);

        $project->update($validated);
        $project->technologies()->sync($validated['technologies']);

        // If it was in REVISION_REQUIRED, put it back to PENDING_REVIEW
        if ($project->status->value === 'REVISION_REQUIRED') {
            $project->update(['status' => \App\Enums\ProjectStatus::PENDING_REVIEW]);
            $this->projectService->logHistory(
                $project,
                auth()->id(),
                'REVISED_AND_RESUBMITTED',
                'REVISION_REQUIRED',
                'PENDING_REVIEW',
                'Owner updated project information and resubmitted for admin review.'
            );
        }

        return redirect()->route('user.projects.show', $project)->with('success', 'Project updated successfully.');
    }
}
