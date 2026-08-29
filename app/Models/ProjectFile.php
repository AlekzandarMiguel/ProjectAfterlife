<?php

namespace App\Models;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int|null $version_id
 * @property int $uploaded_by
 * @property string $file_name
 * @property string $storage_path
 * @property \App\Enums\FileType $file_type
 * @property int $file_size
 * @property string|null $mime_type
 * @property string|null $sha256_hash
 * @property array<mixed>|null $file_tree_json
 * @property bool $is_scanned
 * @property string $security_status
 * @property \Illuminate\Support\Carbon|null $scanned_at
 * @property bool $is_current
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\ProjectVersion|null $version
 * @property-read \App\Models\User $uploader
 */
class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version_id',
        'uploaded_by',
        'file_name',
        'storage_path',
        'file_type',
        'file_size',
        'mime_type',
        'sha256_hash',
        'file_tree_json',
        'is_scanned',
        'security_status',
        'scanned_at',
        'is_current',
    ];

    protected $casts = [
        'file_type' => FileType::class,
        'file_size' => 'integer',
        'file_tree_json' => 'array',
        'is_scanned' => 'boolean',
        'is_current' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(ProjectVersion::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isZipArchive(): bool
    {
        return in_array($this->file_type, [
            FileType::SOURCE_CODE_ZIP,
            FileType::RELEASE_PACKAGE,
        ], true) || str_ends_with(strtolower($this->file_name), '.zip');
    }
}
