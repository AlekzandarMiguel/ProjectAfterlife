<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GitHubImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GitHubImportController extends Controller
{
    public function __construct(protected GitHubImportService $githubService) {}

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $data = $this->githubService->fetchRepositoryMetadata($request->input('url'));

        if (!$data['success']) {
            return response()->json([
                'success' => false,
                'message' => $data['error'] ?? 'Could not import repository information.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
