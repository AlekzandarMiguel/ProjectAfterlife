<?php

namespace App\Models;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property string $file_path
 * @property string $original_filename
 * @property \App\Enums\FileType $file_type
 * @property int $file_size
 * @property string|null $mime_type
 * @property-read \App\Models\Project $project
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
        'is_current',
    ];

    protected $casts = [
        'file_type' => FileType::class,
        'file_size' => 'integer',
        'is_current' => 'boolean',
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
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
