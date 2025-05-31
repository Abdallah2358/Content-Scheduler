<?php

namespace App\Models;

use App\Enums\PlatformTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
    title: 'Platform',
    description: 'Platform model',
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'type', ref: '#/components/schemas/PlatformTypeEnum'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)
]
class Platform extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => PlatformTypeEnum::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the posts published on the platform.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_platform', 'platform_id', 'post_id');
    }

    public function publish(Post $post): string
    {   
        return match ($this->type) {
            PlatformTypeEnum::TWITTER => (new \App\Services\TwitterPublishService($post))->publish(),
            PlatformTypeEnum::INSTAGRAM => (new \App\Services\InstagramPublishService($post))->publish(),
            PlatformTypeEnum::LINKEDIN => (new \App\Services\LinkedInPublishService($post))->publish(),
            PlatformTypeEnum::FACEBOOK => (new \App\Services\FacebookPublishService($post))->publish(),
        };
    }
}
