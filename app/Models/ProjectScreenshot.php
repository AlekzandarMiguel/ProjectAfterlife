<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property string $file_path
 * @property string|null $caption
 * @property int $order
 * @property-read \App\Models\Project $project
 */
class ProjectScreenshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'caption',
        'order_index',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
