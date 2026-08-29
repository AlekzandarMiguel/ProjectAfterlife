<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\RecoveryComment;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecoveryDiscussionController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
            'task_id' => ['nullable', 'exists:recovery_tasks,id'],
        ]);

        $comment = RecoveryComment::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'task_id' => $validated['task_id'] ?? null,
            'comment' => $validated['comment'],
            'is_pinned' => false,
        ]);

        if ($project->user_id !== $user->id) {
            NotificationService::send(
                $project->owner,
                'recovery_comment',
                'New Workspace Note Added',
                "{$user->name} posted a note on project '{$project->title}'.",
                route('user.recovery.workspace', $project)
            );
        }

        NotificationService::notifyAdmins(
            'recovery_comment',
            'Recovery Activity Note',
            "{$user->name} added a collaborative note on '{$project->title}'.",
            route('user.recovery.workspace', $project)
        );

        AuditService::log('RECOVERY_COMMENT_ADDED', $project, [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Discussion note posted to the recovery stream.');
    }

    public function destroy(Project $project, RecoveryComment $comment): RedirectResponse
    {
        $user = auth()->user();

        if ($comment->user_id !== $user->id && $project->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Discussion note removed.');
    }
}
