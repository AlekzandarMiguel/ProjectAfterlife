<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\ProjectVersion;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GitHubWebhookController extends Controller
{
    public function handle(Request $request, Project $project): JsonResponse
    {
        $payload = $request->all();
        $event = $request->header('X-GitHub-Event', 'ping');

        if ($event === 'ping') {
            return response()->json(['message' => 'Pong! Webhook connected successfully.']);
        }

        // Validate webhook signature if project webhook_secret is configured
        $signature = $request->header('X-Hub-Signature-256');
        $secret = config('services.github.webhook_secret') ?? 'afterlife_secret_' . $project->id;
        
        if ($signature && $secret) {
            $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($expectedSignature, $signature)) {
                return response()->json(['error' => 'Invalid signature.'], 403);
            }
        }

        // Handle Release Event
        if ($event === 'release' && isset($payload['release'])) {
            $release = $payload['release'];
            $tagName = $release['tag_name'] ?? 'v1.0.0';
            $body = $release['body'] ?? 'Release synced from GitHub repository.';

            $version = ProjectVersion::create([
                'project_id' => $project->id,
                'uploaded_by' => $project->owner_id,
                'version_number' => $tagName,
                'title' => "Release {$tagName}",
                'description' => 'Release synchronized from GitHub repository.',
                'release_notes' => $body,
                'is_final_release' => false,
            ]);

            $project->update(['last_activity_at' => now()]);

            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => $project->owner_id,
                'action' => 'VERSION_SYNCED_GITHUB',
                'old_status' => $project->status->value,
                'new_status' => $project->status->value,
                'description' => "New release {$tagName} automatically synchronized via GitHub webhook.",
            ]);

            NotificationService::notifyAdmins(
                'github_release_synced',
                'GitHub Release Synced',
                "Project '{$project->title}' automatically published release {$tagName} via GitHub webhook.",
                route('explore.show', $project)
            );

            return response()->json([
                'success' => true,
                'version' => $tagName,
                'message' => 'Release synchronized successfully.',
            ]);
        }

        return response()->json(['message' => "Event '{$event}' received but no action required."]);
    }
}
