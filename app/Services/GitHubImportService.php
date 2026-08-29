<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubImportService
{
    /**
     * Fetch public metadata from a GitHub repository URL
     *
     * @return array{
     *     success: bool,
     *     title: string|null,
     *     short_description: string|null,
     *     repository_url: string,
     *     primary_language: string|null,
     *     license: string|null,
     *     stars_count: int,
     *     forks_count: int,
     *     open_issues_count: int,
     *     is_archived: bool,
     *     last_pushed_at: string|null,
     *     error: string|null
     * }
     */
    public function fetchRepositoryMetadata(string $url): array
    {
        $parsed = $this->parseOwnerAndRepo($url);
        if (!$parsed) {
            return [
                'success' => false,
                'title' => null,
                'short_description' => null,
                'repository_url' => $url,
                'primary_language' => null,
                'license' => null,
                'stars_count' => 0,
                'forks_count' => 0,
                'open_issues_count' => 0,
                'is_archived' => false,
                'last_pushed_at' => null,
                'error' => 'Invalid GitHub repository URL format. Example: https://github.com/owner/repository',
            ];
        }

        [$owner, $repo] = $parsed;
        $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}";

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'ProjectAfterlife-Preservation-Bot/1.0',
                'Accept' => 'application/vnd.github.v3+json',
            ])->timeout(8)->get($apiUrl);

            if ($response->failed()) {
                $status = $response->status();
                $msg = $status === 404 ? 'Repository not found or is private.' : "GitHub API responded with status {$status}.";
                return [
                    'success' => false,
                    'title' => null,
                    'short_description' => null,
                    'repository_url' => $url,
                    'primary_language' => null,
                    'license' => null,
                    'stars_count' => 0,
                    'forks_count' => 0,
                    'open_issues_count' => 0,
                    'is_archived' => false,
                    'last_pushed_at' => null,
                    'error' => $msg,
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'title' => $data['name'] ?? null,
                'short_description' => $data['description'] ?? null,
                'repository_url' => $data['html_url'] ?? $url,
                'primary_language' => $data['language'] ?? null,
                'license' => $data['license']['spdx_id'] ?? ($data['license']['name'] ?? null),
                'stars_count' => (int) ($data['stargazers_count'] ?? 0),
                'forks_count' => (int) ($data['forks_count'] ?? 0),
                'open_issues_count' => (int) ($data['open_issues_count'] ?? 0),
                'is_archived' => (bool) ($data['archived'] ?? false),
                'last_pushed_at' => $data['pushed_at'] ?? null,
                'error' => null,
            ];
        } catch (Exception $e) {
            Log::warning("GitHub import failed for {$url}: " . $e->getMessage());
            return [
                'success' => false,
                'title' => null,
                'short_description' => null,
                'repository_url' => $url,
                'primary_language' => null,
                'license' => null,
                'stars_count' => 0,
                'forks_count' => 0,
                'open_issues_count' => 0,
                'is_archived' => false,
                'last_pushed_at' => null,
                'error' => 'Connection to GitHub API timed out or failed.',
            ];
        }
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    protected function parseOwnerAndRepo(string $url): ?array
    {
        $clean = trim($url);
        if (preg_match('#github\.com/([^/]+)/([^/]+)#i', $clean, $matches)) {
            $owner = $matches[1];
            $repo = preg_replace('/\.git$/i', '', $matches[2]);
            return [$owner, $repo];
        }

        return null;
    }
}
