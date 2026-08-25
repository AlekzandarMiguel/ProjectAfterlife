<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int|null $user_id
 * @property string $event_type
 * @property string $description
 * @property array<string, mixed>|null $metadata
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User|null $user
 */
class ProjectHistory extends Model
{
    use HasFactory;

    protected $table = 'project_history';

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'description',
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
