<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Services\ArchiveInspectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicFilePreviewController extends Controller
{
    public function __construct(protected ArchiveInspectionService $inspectionService) {}

    public function preview(Request $request, Project $project, ProjectFile $file): JsonResponse
    {
        if ($file->project_id !== $project->id) {
            return response()->json(['success' => false, 'error' => 'File does not belong to project.'], 404);
        }

        $path = $request->query('path');
        if (!is_string($path) || trim($path) === '') {
            return response()->json(['success' => false, 'error' => 'Missing file path parameter.'], 400);
        }

        $cleanPath = trim(str_replace('\\', '/', $path));
        // Path traversal defense
        if (str_contains($cleanPath, '../') || str_starts_with($cleanPath, '/')) {
            return response()->json(['success' => false, 'error' => 'Invalid file path.'], 400);
        }

        $absoluteZipPath = Storage::disk('local')->path($file->storage_path);
        $result = $this->inspectionService->readFileContent($absoluteZipPath, $cleanPath);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Could not preview file content.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'filename' => $result['filename'],
            'extension' => $result['extension'],
            'size' => $result['size'],
            'content' => $result['content'],
        ]);
    }
}
