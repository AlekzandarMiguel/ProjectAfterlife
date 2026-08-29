<?php

namespace App\Models;

use App\Enums\TaskPhase;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int|null $assigned_to
 * @property string $title
 * @property string|null $description
 * @property \App\Enums\TaskPhase $phase
 * @property \App\Enums\TaskPriority $priority
 * @property \App\Enums\TaskStatus $status
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User|null $assignee
 */
class RecoveryTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'phase',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'order_index',
    ];

    protected $casts = [
        'phase' => TaskPhase::class,
        'priority' => TaskPriority::class,
        'status' => TaskStatus::class,
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'order_index' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
