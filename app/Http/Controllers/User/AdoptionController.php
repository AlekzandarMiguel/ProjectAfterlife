<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdoptionRequest;
use App\Models\AdoptionRequest;
use App\Models\Project;
use App\Services\AdoptionService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdoptionController extends Controller
{
    public function __construct(protected AdoptionService $adoptionService) {}

    public function index(): View
    {
        $user = auth()->user();
        $requests = AdoptionRequest::with(['project.category', 'project.owner', 'reviewer'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.adoptions.index', compact('requests'));
    }

    public function create(Project $project): View|RedirectResponse
    {
        if (!$project->canBeAdoptedBy(auth()->user())) {
            return redirect()->route('explore.show', $project)->with('error', 'You cannot apply to adopt this project.');
        }

        return view('user.adoptions.create', compact('project'));
    }

    public function store(StoreAdoptionRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->adoptionService->submitAdoptionRequest($project, auth()->user(), $request->validated());
            return redirect()->route('user.adoptions.index')
                ->with('success', "Your adoption proposal for '{$project->title}' was successfully submitted and is under Administrator review.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(AdoptionRequest $adoptionRequest): View
    {
        if ($adoptionRequest->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $adoptionRequest->load(['project.category', 'project.owner', 'applicant', 'reviewer']);
        return view('user.adoptions.show', compact('adoptionRequest'));
    }
}
