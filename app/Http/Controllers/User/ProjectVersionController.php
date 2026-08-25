<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVersionRequest;
use App\Models\Project;
use App\Services\RecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectVersionController extends Controller
{
    public function __construct(protected RecoveryService $recoveryService) {}

    public function index(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load(['versions.uploader', 'versions.files']);

        return view('user.versions.index', compact('project'));
    }

    public function store(StoreVersionRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('manageRecovery', $project);

        $this->recoveryService->releaseVersion(
            $project,
            auth()->user(),
            $request->validated(),
            $request->file('source_zip')
        );

        return back()->with('success', "New version '{$request->input('version_number')}' published successfully!");
    }
}
