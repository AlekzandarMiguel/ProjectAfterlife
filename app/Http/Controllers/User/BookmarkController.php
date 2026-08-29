<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $projects = $user->bookmarkedProjects()
            ->with(['category', 'technologies', 'owner', 'originalOwner'])
            ->latest('project_bookmarks.created_at')
            ->paginate(12);

        return view('user.bookmarks.index', compact('projects'));
    }

    public function toggle(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $wasBookmarked = $user->bookmarkedProjects()->where('project_id', $project->id)->exists();

        if ($wasBookmarked) {
            $user->bookmarkedProjects()->detach($project->id);
            $isBookmarked = false;
            $message = "Removed '{$project->title}' from your watchlist.";
        } else {
            $user->bookmarkedProjects()->attach($project->id);
            $isBookmarked = true;
            $message = "Added '{$project->title}' to your watchlist.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_bookmarked' => $isBookmarked,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
