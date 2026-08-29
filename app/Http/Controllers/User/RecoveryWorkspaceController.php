<?php

namespace App\Http\Controllers\User;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecoveryTaskRequest;
use App\Models\Project;
use App\Services\AdoptionService;
use App\Models\RecoveryTask;
use App\Services\RecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecoveryWorkspaceController extends Controller
{
    public function __construct(protected RecoveryService $recoveryService) {}

    public function index(): View
    {
        $user = auth()->user();
        $projects = Project::with(['category', 'technologies', 'recoveryTasks', 'latestVersion'])
            ->where('owner_id', $user->id)
            ->whereIn('status', [\App\Enums\ProjectStatus::ADOPTED, \App\Enums\ProjectStatus::UNDER_RECOVERY])
            ->latest('last_activity_at')
            ->paginate(8);

        return view('user.recovery.index', compact('projects'));
    }

    public function workspace(Project $project): View
    {
        $this->authorize('manageRecovery', $project);

        $project->load([
            'category',
            'technologies',
            'originalOwner',
            'owner',
            'recoveryTasks' => fn($q) => $q->orderBy('order_index'),
            'recoveryUpdates.user',
            'versions.files',
            'versions.uploader',
            'files',
            'history.user',
        ]);

        $tasks = $project->recoveryTasks;
        $progress = $project->recovery_progress;
        $totalTasks = $project->total_tasks_count;
        $completedTasks = $project->completed_tasks_count;

        return view('user.recovery.workspace', compact('project', 'tasks', 'progress', 'totalTasks', 'completedTasks'));
    }

    public function storeTask(StoreRecoveryTaskRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('manageRecovery', $project);

        $this->recoveryService->createTask($project, auth()->user(), $request->validated());

        return back()->with('success', 'Recovery task added successfully.');
    }

    public function updateTaskStatus(Request $request, Project $project, RecoveryTask $task): RedirectResponse
    {
        $this->authorize('manageRecovery', $project);

        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:todo,in_progress,completed'],
        ]);

        $statusEnum = match($validated['status']) {
            'in_progress' => TaskStatus::IN_PROGRESS,
            'completed' => TaskStatus::COMPLETED,
            default => TaskStatus::TODO,
        };

        $this->recoveryService->updateTaskStatus($task, $statusEnum, auth()->user());

        return back()->with('success', "Task '{$task->title}' updated to {$statusEnum->label()}.");
    }

    public function storeUpdate(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('manageRecovery', $project);

        $validated = $request->validate([
            'update_title' => ['required', 'string', 'max:150'],
            'update_text' => ['required', 'string', 'min:10'],
        ]);

        $this->recoveryService->addRecoveryUpdate($project, auth()->user(), $validated['update_title'], $validated['update_text']);

        return back()->with('success', 'Recovery progress update logged.');
    }

    public function relinquish(Request $request, Project $project, AdoptionService $adoptionService): RedirectResponse
    {
        if (auth()->id() !== $project->owner_id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'relinquish_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        try {
            $adoptionService->relinquishStewardship($project, auth()->user(), $validated['relinquish_reason']);

            return redirect()->route('user.projects.index')
                ->with('success', "Stewardship of '{$project->title}' was successfully relinquished and returned to the preservation registry.");
        } catch (\Exception $e) {
            return back()->with('error', 'Relinquishment failed: ' . $e->getMessage());
        }
    }
}
