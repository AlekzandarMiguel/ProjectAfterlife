<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdoptionStatus;
use App\Enums\FinalReviewStatus;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\AuditLog;
use App\Models\FinalReviewSubmission;
use App\Models\OwnershipTransfer;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Admin telemetry metrics
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'pending_submissions' => Project::where('status', ProjectStatus::PENDING_REVIEW)->count(),
            'available_projects' => Project::where('status', ProjectStatus::AVAILABLE)->count(),
            'pending_adoptions' => AdoptionRequest::where('status', AdoptionStatus::PENDING)->count(),
            'active_recoveries' => Project::whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY])->count(),
            'pending_final_reviews' => FinalReviewSubmission::where('status', FinalReviewStatus::PENDING)->count(),
            'resurrected_projects' => Project::where('status', ProjectStatus::RESURRECTED)->count(),
            'reabandoned_projects' => Project::where('status', ProjectStatus::ABANDONED_AGAIN)->count(),
            'total_transfers' => OwnershipTransfer::count(),
        ];

        // Action Queues
        $pendingSubmissions = Project::with(['category', 'owner', 'technologies'])
            ->where('status', ProjectStatus::PENDING_REVIEW)
            ->latest()
            ->take(5)
            ->get();

        $pendingAdoptions = AdoptionRequest::with(['project', 'applicant'])
            ->where('status', AdoptionStatus::PENDING)
            ->latest()
            ->take(5)
            ->get();

        $pendingFinalReviews = FinalReviewSubmission::with(['project.owner', 'version'])
            ->where('status', FinalReviewStatus::PENDING)
            ->latest()
            ->take(5)
            ->get();

        $recentTransfers = OwnershipTransfer::with(['project', 'previousOwner', 'newOwner', 'adminApprover'])
            ->latest('transferred_at')
            ->take(5)
            ->get();

        $recentAudits = AuditLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'pendingSubmissions', 'pendingAdoptions', 'pendingFinalReviews', 'recentTransfers', 'recentAudits'));
    }
}
