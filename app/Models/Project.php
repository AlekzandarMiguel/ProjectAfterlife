<?php

namespace App\Models;

use App\Enums\DevelopmentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $original_user_id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string $tagline
 * @property string $description
 * @property string $reason_for_abandonment
 * @property string|null $architecture_notes
 * @property \App\Enums\ProjectType $project_type
 * @property \App\Enums\DevelopmentStatus $development_status
 * @property \App\Enums\ProjectStatus $status
 * @property int $recovery_progress
 * @property string|null $rejection_reason
 * @property string|null $source_repository_url
 * @property string|null $demo_url
 * @property \Illuminate\Support\Carbon|null $resurrected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\User|null $originalOwner
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Technology> $technologies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectVersion> $versions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectFile> $files
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectScreenshot> $screenshots
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdoptionRequest> $adoptionRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecoveryTask> $recoveryTasks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecoveryUpdate> $recoveryUpdates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinalReviewSubmission> $finalReviews
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OwnershipTransfer> $ownershipTransfers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectHistory> $history
 * @property-read \App\Models\OwnershipDeclaration|null $ownershipDeclaration
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'original_owner_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'project_type',
        'development_status',
        'reason_for_abandonment',
        'original_development_date',
        'last_development_date',
        'status',
        'admin_review_notes',
        'revision_instructions',
        'is_featured',
        'published_at',
        'resurrected_at',
        'last_activity_at',
    ];

    protected $casts = [
        'project_type' => ProjectType::class,
        'development_status' => DevelopmentStatus::class,
        'status' => ProjectStatus::class,
        'original_development_date' => 'date',
        'last_development_date' => 'date',
        'published_at' => 'datetime',
        'resurrected_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title) . '-' . Str::random(6);
            }
            if (empty($project->last_activity_at)) {
                $project->last_activity_at = now();
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function originalOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_owner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'project_technologies')->withTimestamps();
    }

    public function ownershipDeclarations(): HasMany
    {
        return $this->hasMany(OwnershipDeclaration::class);
    }

    public function latestDeclaration(): HasOne
    {
        return $this->hasOne(OwnershipDeclaration::class)->latestOfMany();
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(ProjectScreenshot::class)->orderBy('order_index');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProjectVersion::class)->orderByDesc('created_at');
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(ProjectVersion::class)->latestOfMany();
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class)->orderByDesc('created_at');
    }

    public function pendingAdoptionRequest(): HasOne
    {
        return $this->hasOne(AdoptionRequest::class)->where('status', 'pending');
    }

    public function ownershipTransfers(): HasMany
    {
        return $this->hasMany(OwnershipTransfer::class)->orderByDesc('transferred_at');
    }

    public function recoveryTasks(): HasMany
    {
        return $this->hasMany(RecoveryTask::class)->orderBy('order_index');
    }

    public function recoveryUpdates(): HasMany
    {
        return $this->hasMany(RecoveryUpdate::class)->orderByDesc('created_at');
    }

    public function finalReviewSubmissions(): HasMany
    {
        return $this->hasMany(FinalReviewSubmission::class)->orderByDesc('created_at');
    }

    public function latestFinalReview(): HasOne
    {
        return $this->hasOne(FinalReviewSubmission::class)->latestOfMany();
    }

    public function history(): HasMany
    {
        return $this->hasMany(ProjectHistory::class)->orderByDesc('created_at');
    }

    public function getRecoveryProgressAttribute(): int
    {
        $total = $this->recoveryTasks()->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->recoveryTasks()->where('status', TaskStatus::COMPLETED)->count();
        return (int) round(($completed / $total) * 100);
    }

    public function getTotalTasksCountAttribute(): int
    {
        return $this->recoveryTasks()->count();
    }

    public function getCompletedTasksCountAttribute(): int
    {
        return $this->recoveryTasks()->where('status', TaskStatus::COMPLETED)->count();
    }

    public function isAvailable(): bool
    {
        return $this->status === ProjectStatus::AVAILABLE;
    }

    public function isUnderRecovery(): bool
    {
        return in_array($this->status, [ProjectStatus::ADOPTED, ProjectStatus::UNDER_RECOVERY]);
    }

    public function isResurrected(): bool
    {
        return $this->status === ProjectStatus::RESURRECTED;
    }

    public function canBeAdoptedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        if ($this->owner_id === $user->id) {
            return false;
        }
        if (!$this->isAvailable()) {
            return false;
        }
        $hasPending = $this->adoptionRequests()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        return !$hasPending;
    }
}
