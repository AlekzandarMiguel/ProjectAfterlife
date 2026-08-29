<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ArchiveInspectionService
{
    /**
     * Dangerous file extensions prohibited inside preserved archives
     */
    protected const PROHIBITED_EXTENSIONS = [
        'exe', 'dll', 'so', 'dylib', 'bat', 'cmd', 'ps1', 'vbs', 'vbe', 
        'js', 'jse', 'wsf', 'wsh', 'msc', 'pif', 'scr', 'cpl', 'jar',
        'com', 'gadget', 'msi', 'msp', 'hta', 'csh', 'inf', 'reg', 'phar'
    ];

    /**
     * Inspect a ZIP archive from absolute filesystem path.
     * Returns an array with status, file tree, sha256 checksum, stats, and security findings.
     *
     * @return array{
     *     is_valid: bool,
     *     security_status: string,
     *     sha256_hash: string,
     *     total_files: int,
     *     total_dirs: int,
     *     uncompressed_size: int,
     *     threats: array<string>,
     *     file_tree: array<mixed>
     * }
     */
    public function inspect(string $absoluteFilePath): array
    {
        if (!file_exists($absoluteFilePath)) {
            return [
                'is_valid' => false,
                'security_status' => 'missing_file',
                'sha256_hash' => '',
                'total_files' => 0,
                'total_dirs' => 0,
                'uncompressed_size' => 0,
                'threats' => ['File not found on disk.'],
                'file_tree' => [],
            ];
        }

        $sha256 = hash_file('sha256', $absoluteFilePath) ?: '';
        $zip = new ZipArchive();
        $res = $zip->open($absoluteFilePath, ZipArchive::RDONLY);

        if ($res !== true) {
            return [
                'is_valid' => false,
                'security_status' => 'corrupted_archive',
                'sha256_hash' => $sha256,
                'total_files' => 0,
                'total_dirs' => 0,
                'uncompressed_size' => 0,
                'threats' => ['Could not read valid ZIP archive structure.'],
                'file_tree' => [],
            ];
        }

        $totalFiles = 0;
        $totalDirs = 0;
        $uncompressedSize = 0;
        $threats = [];
        $tree = [];

        try {
            $numFiles = $zip->numFiles;

            // Protection against Zip Bombs with excessive file counts
            if ($numFiles > 10000) {
                $threats[] = 'Archive contains excessive file count (> 10,000 files).';
            }

            for ($i = 0; $i < $numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (!$stat) {
                    continue;
                }

                $filename = str_replace('\\', '/', $stat['name']);
                $size = (int) $stat['size'];
                $uncompressedSize += $size;

                // 1. Path Traversal Threat Check
                if (str_contains($filename, '../') || str_starts_with($filename, '/')) {
                    $threats[] = "Potentially dangerous path traversal sequence detected: {$filename}";
                }

                $isDir = str_ends_with($filename, '/');
                if ($isDir) {
                    $totalDirs++;
                } else {
                    $totalFiles++;
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    // 2. Binary / Script Threat Check
                    if (in_array($ext, self::PROHIBITED_EXTENSIONS, true)) {
                        $threats[] = "Prohibited executable/binary file extension detected: {$filename}";
                    }
                }

                // Build tree node (limit tree depth/size to first 500 entries for JSON performance)
                if ($i < 500) {
                    $this->addNodeToTree($tree, $filename, $size, $isDir);
                }
            }
        } catch (Exception $e) {
            Log::warning('Archive inspection exception: ' . $e->getMessage());
            $threats[] = 'Archive parsing encountered an internal exception: ' . $e->getMessage();
        } finally {
            $zip->close();
        }

        $securityStatus = empty($threats) ? 'clean' : 'suspicious';

        return [
            'is_valid' => empty($threats),
            'security_status' => $securityStatus,
            'sha256_hash' => $sha256,
            'total_files' => $totalFiles,
            'total_dirs' => $totalDirs,
            'uncompressed_size' => $uncompressedSize,
            'threats' => $threats,
            'file_tree' => $tree,
        ];
    }

    /**
     * Add a path into nested JSON array structure
     *
     * @param array<string, mixed> $tree
     */
    protected function addNodeToTree(array &$tree, string $path, int $size, bool $isDir): void
    {
        $parts = array_values(array_filter(explode('/', trim($path, '/'))));
        if (empty($parts)) {
            return;
        }

        $current = &$tree;
        $count = count($parts);

        for ($i = 0; $i < $count; $i++) {
            $part = $parts[$i];
            $isLast = ($i === $count - 1);

            if ($isLast && !$isDir) {
                $current[$part] = [
                    'type' => 'file',
                    'name' => $part,
                    'path' => $path,
                    'size' => $size,
                    'extension' => strtolower(pathinfo($part, PATHINFO_EXTENSION)),
                ];
            } else {
                if (!isset($current[$part]) || !is_array($current[$part]) || ($current[$part]['type'] ?? '') !== 'directory') {
                    $current[$part] = [
                        'type' => 'directory',
                        'name' => $part,
                        'children' => [],
                    ];
                }
                $current = &$current[$part]['children'];
            }
        }
    }

    /**
     * Previewable safe text extensions
     */
    protected const PREVIEWABLE_EXTENSIONS = [
        'md', 'txt', 'json', 'yml', 'yaml', 'xml', 'csv', 'sql', 
        'env.example', 'gitignore', 'gitattributes', 'editorconfig',
        'php', 'js', 'ts', 'jsx', 'tsx', 'py', 'rb', 'go', 'rs', 
        'html', 'css', 'scss', 'sh', 'c', 'cpp', 'h', 'java', 'kt'
    ];

    /**
     * Read and return text content of an individual file inside a ZIP archive.
     * Enforces a maximum byte threshold (default 256KB) to protect against OOM.
     */
    public function readFileContent(string $absoluteZipPath, string $internalFilePath, int $maxBytes = 262144): array
    {
        if (!file_exists($absoluteZipPath)) {
            return ['success' => false, 'content' => null, 'error' => 'Archive file not found.'];
        }

        $ext = strtolower(pathinfo($internalFilePath, PATHINFO_EXTENSION));
        $basename = strtolower(basename($internalFilePath));

        // Check if extension or filename is allowed for text preview
        $isAllowed = in_array($ext, self::PREVIEWABLE_EXTENSIONS, true) || in_array($basename, ['.env.example', 'dockerfile', 'makefile', 'license', 'readme'], true);
        if (!$isAllowed) {
            return ['success' => false, 'content' => null, 'error' => 'File type not supported for text preview.'];
        }

        $zip = new ZipArchive();
        if ($zip->open($absoluteZipPath, ZipArchive::RDONLY) !== true) {
            return ['success' => false, 'content' => null, 'error' => 'Could not read archive.'];
        }

        try {
            $stat = $zip->statName($internalFilePath);
            if (!$stat) {
                // Try with normalized slash
                $stat = $zip->statName(ltrim($internalFilePath, '/'));
            }

            if (!$stat) {
                return ['success' => false, 'content' => null, 'error' => 'File not found inside archive.'];
            }

            $size = (int) $stat['size'];
            if ($size > $maxBytes) {
                return ['success' => false, 'content' => null, 'error' => 'File is too large for in-browser preview (> 256KB). Please download the archive.'];
            }

            $stream = $zip->getStream($stat['name']);
            if (!$stream) {
                return ['success' => false, 'content' => null, 'error' => 'Could not open file stream inside archive.'];
            }

            $content = stream_get_contents($stream, $maxBytes);
            fclose($stream);

            if ($content === false) {
                return ['success' => false, 'content' => null, 'error' => 'Failed to read file content.'];
            }

            // UTF-8 sanitize
            if (!mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
            }

            return [
                'success' => true,
                'content' => $content,
                'filename' => basename($internalFilePath),
                'extension' => $ext ?: 'txt',
                'size' => $size,
                'error' => null,
            ];
        } finally {
            $zip->close();
        }
    }
}
