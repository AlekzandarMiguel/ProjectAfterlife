<?php

namespace App\Models;

use App\Enums\TechType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \App\Enums\TechType $type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 */
class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    protected $casts = [
        'type' => TechType::class,
    ];

    protected static function booted()
    {
        static::creating(function ($tech) {
            if (empty($tech->slug)) {
                $tech->slug = Str::slug($tech->name);
            }
        });
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_technologies')->withTimestamps();
    }
}
