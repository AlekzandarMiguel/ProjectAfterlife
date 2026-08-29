<?php

namespace App\Models;

use App\Enums\FinalReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $summary_of_changes
 * @property string|null $test_coverage_notes
 * @property string|null $live_demo_url
 * @property string|null $release_version
 * @property \App\Enums\FinalReviewStatus $status
 * @property string|null $admin_feedback
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $submitter
 * @property-read \App\Models\User|null $reviewer
 */
class FinalReviewSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version_id',
        'submitted_by',
        'completion_summary',
        'completed_features',
        'testing_summary',
        'status',
        'admin_feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'status' => FinalReviewStatus::class,
        'reviewed_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(ProjectVersion::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
