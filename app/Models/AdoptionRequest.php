<?php

namespace App\Models;

use App\Enums\AdoptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $proposal
 * @property string $technical_qualification
 * @property string $estimated_timeline
 * @property \App\Enums\AdoptionStatus $status
 * @property string|null $admin_notes
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $applicant
 * @property-read \App\Models\User|null $reviewer
 */
class AdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'reason',
        'proposed_improvements',
        'recovery_plan',
        'expected_completion_date',
        'relevant_skills',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'status' => AdoptionStatus::class,
        'expected_completion_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function ownershipTransfer(): HasOne
    {
        return $this->hasOne(OwnershipTransfer::class);
    }
}
