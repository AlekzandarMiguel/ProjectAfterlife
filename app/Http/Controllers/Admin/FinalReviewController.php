<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FinalReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\FinalReviewSubmission;
use App\Services\RecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalReviewController extends Controller
{
    public function __construct(protected RecoveryService $recoveryService) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $reviews = FinalReviewSubmission::with(['project.category', 'project.owner', 'version', 'submitter', 'reviewer'])
            ->where('status', $status)
            ->latest()
            ->paginate(12);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function show(FinalReviewSubmission $finalReview): View
    {
        $finalReview->load([
            'project.category',
            'project.technologies',
            'project.owner.profile',
            'project.originalOwner.profile',
            'project.recoveryTasks',
            'project.recoveryUpdates',
            'project.versions.files',
            'project.history.user',
            'version',
            'submitter.profile',
            'reviewer',
        ]);

        return view('admin.reviews.show', compact('finalReview'));
    }

    public function approve(Request $request, FinalReviewSubmission $finalReview): RedirectResponse
    {
        $validated = $request->validate([
            'admin_feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->recoveryService->approveResurrection($finalReview, auth()->user(), $validated['admin_feedback'] ?? null);

        return redirect()->route('admin.final-reviews.index')
            ->with('success', "Project '{$finalReview->project->title}' was approved and certified as RESURRECTED!");
    }

    public function requestRevision(Request $request, FinalReviewSubmission $finalReview): RedirectResponse
    {
        $validated = $request->validate([
            'admin_feedback' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $project = $finalReview->project;
        $finalReview->update([
            'status' => FinalReviewStatus::REVISION_REQUIRED,
            'admin_feedback' => $validated['admin_feedback'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $project->update(['status' => \App\Enums\ProjectStatus::UNDER_RECOVERY]);

        \App\Models\ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'FINAL_REVIEW_REVISION_REQUESTED',
            'old_status' => 'PENDING_FINAL_REVIEW',
            'new_status' => 'UNDER_RECOVERY',
            'description' => "Admin requested revisions before resurrection. Notes: {$validated['admin_feedback']}",
        ]);

        \App\Services\NotificationService::send(
            $project->owner,
            'final_review_revision',
            'Revisions Required for Project Resurrection',
            "Administrator feedback on your final review for '{$project->title}': {$validated['admin_feedback']}",
            route('user.recovery.workspace', $project)
        );

        \App\Services\AuditService::log('FINAL_REVIEW_REVISION_REQUESTED', $finalReview);

        return redirect()->route('admin.final-reviews.index')
            ->with('success', "Revision instructions sent to owner.");
    }
}
