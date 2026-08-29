<?php

namespace App\Http\Controllers\User;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyProjectsController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tab = $request->input('tab', 'uploaded'); // uploaded | adopted

        if ($tab === 'adopted') {
            $projects = Project::with(['category', 'technologies', 'originalOwner', 'recoveryTasks'])
                ->where('owner_id', $user->id)
                ->where('original_owner_id', '!=', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $projects = Project::with(['category', 'technologies', 'owner', 'recoveryTasks'])
                ->where('original_owner_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('user.projects.index', compact('projects', 'tab'));
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load([
            'category',
            'technologies',
            'owner.profile',
            'originalOwner.profile',
            'versions.files',
            'versions.uploader',
            'files',
            'screenshots',
            'ownershipTransfers.previousOwner',
            'ownershipTransfers.newOwner',
            'recoveryTasks',
            'recoveryUpdates.user',
            'history.user',
        ]);

        return view('user.projects.show', compact('project'));
    }
}
