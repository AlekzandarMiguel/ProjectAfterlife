<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinalReviewRequest;
use App\Models\Project;
use App\Services\RecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FinalReviewController extends Controller
{
    public function __construct(protected RecoveryService $recoveryService) {}

    public function create(Project $project): View
    {
        $this->authorize('manageRecovery', $project);
        $project->load(['versions', 'recoveryTasks']);

        return view('user.recovery.final-review', compact('project'));
    }

    public function store(StoreFinalReviewRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('manageRecovery', $project);

        $this->recoveryService->submitFinalReview($project, auth()->user(), $request->validated());

        return redirect()->route('user.recovery.workspace', $project)
            ->with('success', 'Final recovery submission sent! The Administrator will review your work for official Resurrection.');
    }
}
