<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdoptionStatus;
use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Services\AdoptionService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdoptionReviewController extends Controller
{
    public function __construct(protected AdoptionService $adoptionService) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $requests = AdoptionRequest::with(['project.category', 'project.owner', 'applicant.profile', 'reviewer'])
            ->where('status', $status)
            ->latest()
            ->paginate(12);

        return view('admin.adoptions.index', compact('requests', 'status'));
    }

    public function show(AdoptionRequest $adoptionRequest): View
    {
        $adoptionRequest->load([
            'project.category',
            'project.technologies',
            'project.owner.profile',
            'project.originalOwner.profile',
            'project.ownershipTransfers.newOwner',
            'applicant.profile',
            'applicant.ownedProjects',
            'reviewer',
        ]);

        return view('admin.adoptions.show', compact('adoptionRequest'));
    }

    public function approve(Request $request, AdoptionRequest $adoptionRequest): RedirectResponse
    {
        $validated = $request->validate([
            'admin_password' => ['required', 'string'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var \App\Models\User $admin */
        $admin = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($validated['admin_password'], $admin->password)) {
            return back()->withErrors(['admin_password' => 'Invalid administrator password. Security verification failed.'])->withInput();
        }

        try {
            $transfer = $this->adoptionService->approveAdoptionAndTransferOwnership(
                $adoptionRequest,
                $admin,
                $validated['admin_notes'] ?? null
            );

            return redirect()->route('admin.ownership-transfers.index')
                ->with('success', "Adoption approved! Ownership of '{$adoptionRequest->project->title}' was successfully transferred to {$adoptionRequest->applicant->name}.");
        } catch (Exception $e) {
            return back()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, AdoptionRequest $adoptionRequest): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $this->adoptionService->rejectAdoptionRequest($adoptionRequest, auth()->user(), $validated['rejection_reason']);

        return redirect()->route('admin.adoption-requests.index')
            ->with('success', "Adoption request rejected. Project has returned to Available status.");
    }
}
