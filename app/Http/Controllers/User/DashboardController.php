<?php

namespace App\Http\Controllers\User;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\RecoveryTask;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Database-driven user statistics
        $stats = [
            'my_uploaded_count' => Project::where('original_owner_id', $user->id)->count(),
            'my_adopted_count' => Project::where('owner_id', $user->id)
                ->where('original_owner_id', '!=', $user->id)
                ->count(),
            'pending_requests_count' => AdoptionRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'active_recoveries_count' => Project::where('owner_id', $user->id)
                ->whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY])
                ->count(),
            'resurrected_count' => Project::where('owner_id', $user->id)
                ->where('status', ProjectStatus::RESURRECTED)
                ->count(),
        ];

        // Active recovery projects owned by user
        $recoveryProjects = Project::with(['category', 'technologies', 'recoveryTasks'])
            ->where('owner_id', $user->id)
            ->whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY])
            ->latest('last_activity_at')
            ->take(4)
            ->get();

        // Pending adoption requests submitted by user
        $pendingAdoptions = AdoptionRequest::with(['project.category', 'project.owner'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent Activity on owned or uploaded projects
        $projectIds = Project::where('owner_id', $user->id)
            ->orWhere('original_owner_id', $user->id)
            ->pluck('id');

        $recentActivity = ProjectHistory::with(['project', 'user'])
            ->whereIn('project_id', $projectIds)
            ->latest()
            ->take(6)
            ->get();

        // Assigned recovery tasks due soon
        $pendingTasks = RecoveryTask::with('project')
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recoveryProjects', 'pendingAdoptions', 'recentActivity', 'pendingTasks'));
    }
}
