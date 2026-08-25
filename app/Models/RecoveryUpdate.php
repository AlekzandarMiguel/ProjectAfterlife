<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $author
 */
class RecoveryUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'update_title',
        'update_text',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
