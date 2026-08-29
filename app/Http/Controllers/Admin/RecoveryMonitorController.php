<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecoveryMonitorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['owner', 'originalOwner', 'category', 'recoveryTasks', 'latestVersion'])
            ->whereIn('status', [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY, ProjectStatus::INACTIVE, ProjectStatus::ABANDONED_AGAIN]);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $projects = $query->orderBy('last_activity_at')->paginate(12)->withQueryString();

        return view('admin.recovery.index', compact('projects'));
    }

    public function sendWarning(Project $project): RedirectResponse
    {
        NotificationService::send(
            $project->owner,
            'inactivity_warning',
            'Inactivity Warning: Recovery Activity Required',
            "Your adopted project '{$project->title}' has had no recorded recovery activity. Please update tasks or release a version to keep ownership active.",
            route('user.recovery.workspace', $project)
        );

        AuditService::log('INACTIVITY_WARNING_SENT', $project, ['owner_id' => $project->owner_id]);

        return back()->with('success', "Inactivity warning sent to {$project->owner->name}.");
    }

    public function markInactive(Project $project): RedirectResponse
    {
        $oldStatus = $project->status->value;
        $project->update(['status' => ProjectStatus::INACTIVE]);

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'PROJECT_MARKED_INACTIVE',
            'old_status' => $oldStatus,
            'new_status' => ProjectStatus::INACTIVE->value,
            'description' => "Project marked INACTIVE due to prolonged lack of recovery activity.",
        ]);

        NotificationService::send(
            $project->owner,
            'project_inactive',
            'Project Marked Inactive',
            "Your project '{$project->title}' was marked as Inactive due to extended inactivity.",
            route('user.recovery.workspace', $project)
        );

        AuditService::log('PROJECT_MARKED_INACTIVE', $project);

        return back()->with('success', "Project '{$project->title}' marked as Inactive.");
    }

    public function markAbandonedAgain(Project $project): RedirectResponse
    {
        $oldStatus = $project->status->value;
        $project->update(['status' => ProjectStatus::ABANDONED_AGAIN]);

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'PROJECT_REABANDONED',
            'old_status' => $oldStatus,
            'new_status' => ProjectStatus::ABANDONED_AGAIN->value,
            'description' => "Project officially classified as ABANDONED AGAIN by Administrator.",
        ]);

        NotificationService::send(
            $project->owner,
            'project_reabandoned',
            'Project Classified as Re-Abandoned',
            "Due to non-response, '{$project->title}' has been re-classified as Abandoned Again and may be reopened for adoption.",
            route('user.projects.show', $project)
        );

        AuditService::log('PROJECT_REABANDONED', $project);

        return back()->with('success', "Project marked as Abandoned Again.");
    }

    public function reopenForAdoption(Project $project): RedirectResponse
    {
        $oldStatus = $project->status->value;
        $project->update([
            'status' => ProjectStatus::AVAILABLE,
            'published_at' => now(),
            'last_activity_at' => now(),
        ]);

        ProjectHistory::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'PROJECT_REOPENED_FOR_ADOPTION',
            'old_status' => $oldStatus,
            'new_status' => ProjectStatus::AVAILABLE->value,
            'description' => "Project reopened for community adoption by Administrator.",
        ]);

        AuditService::log('PROJECT_REOPENED_FOR_ADOPTION', $project);

        return back()->with('success', "Project '{$project->title}' is now Available for adoption once again!");
    }
}
