<?php

namespace App\Models;

use App\Enums\AdoptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
