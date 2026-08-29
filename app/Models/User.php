<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property string $password
 * @property \App\Enums\UserRole $role
 * @property \App\Enums\UserStatus $status
 * @property string|null $avatar
 * @property string|null $github_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdoptionRequest> $adoptionRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'github_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function uploadedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'original_owner_id');
    }

    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class, 'user_id');
    }

    public function ownershipTransfersReceived(): HasMany
    {
        return $this->hasMany(OwnershipTransfer::class, 'new_owner_id');
    }

    public function ownershipTransfersGiven(): HasMany
    {
        return $this->hasMany(OwnershipTransfer::class, 'previous_owner_id');
    }

    public function recoveryTasks(): HasMany
    {
        return $this->hasMany(RecoveryTask::class, 'assigned_to');
    }

    public function recoveryUpdates(): HasMany
    {
        return $this->hasMany(RecoveryUpdate::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0f172a&color=f8fafc&bold=true';
    }
}
