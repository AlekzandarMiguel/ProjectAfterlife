<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property bool $is_original_creator
 * @property bool $has_right_to_transfer
 * @property string $ip_address
 * @property string $user_agent
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 */
class OwnershipDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'declaration_text',
        'ip_address',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
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
