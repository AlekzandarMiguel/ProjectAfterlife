<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $event
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<string, mixed>|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\User|null $user
 */
class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?Model $entity = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity ? $entity->getKey() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
