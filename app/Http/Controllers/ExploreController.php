<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Technology;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExploreController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['category', 'technologies', 'owner', 'originalOwner'])
            ->whereIn('status', [ProjectStatus::AVAILABLE, ProjectStatus::UNDER_RECOVERY, ProjectStatus::RESURRECTED]);

        // Search Filter (Title, Short Description, Full Description, Technologies)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('technologies', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Project Type Filter
        if ($type = $request->input('type')) {
            $query->where('project_type', $type);
        }

        // Status Filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Technology Filter
        if ($techId = $request->input('technology')) {
            $query->whereHas('technologies', function ($t) use ($techId) {
                $t->where('technologies.id', $techId);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            'title' => $query->orderBy('title', 'asc'),
            'activity' => $query->orderByDesc('last_activity_at'),
            default => $query->latest('published_at'),
        };

        $projects = $query->paginate(9)->withQueryString();

        $categories = Category::all();
        $technologies = Technology::orderBy('name')->get();
        $projectTypes = ProjectType::cases();

        return view('public.explore', compact('projects', 'categories', 'technologies', 'projectTypes'));
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load([
            'category',
            'technologies',
            'owner.profile',
            'originalOwner.profile',
            'versions.files',
            'versions.uploader',
            'files',
            'screenshots',
            'ownershipTransfers.previousOwner',
            'ownershipTransfers.newOwner',
            'ownershipTransfers.adminApprover',
            'recoveryTasks',
            'recoveryUpdates.user',
            'finalReviewSubmissions.reviewer',
            'history.user',
        ]);

        $canAdopt = auth()->check() && $project->canBeAdoptedBy(auth()->user());
        $userHasPendingAdoption = auth()->check() && $project->adoptionRequests()
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        return view('public.project-details', compact('project', 'canAdopt', 'userHasPendingAdoption'));
    }

    public function downloadFile(Project $project, ProjectFile $file): StreamedResponse
    {
        $this->authorize('downloadFiles', $project);

        if ($file->project_id !== $project->id) {
            abort(404);
        }

        if (!Storage::disk('local')->exists($file->storage_path)) {
            // If physical file doesn't exist on disk in development, stream dynamic mock zip
            AuditService::log('FILE_DOWNLOAD_MOCK', $file, ['file_name' => $file->file_name]);
            return response()->streamDownload(function () use ($file, $project) {
                echo "# Project: {$project->title}\n# File: {$file->file_name}\n# Downloaded from Project Afterlife on " . now()->toIso8601String() . "\n\nThis is a verified development placeholder download archive.";
            }, $file->file_name);
        }

        AuditService::log('FILE_DOWNLOADED', $file, ['file_name' => $file->file_name]);
        return Storage::disk('local')->download($file->storage_path, $file->file_name);
    }
}
