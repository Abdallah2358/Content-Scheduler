<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="Post",
 *     required={"title", "content", "status", "user_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Unique identifier for the post"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="My first post",
 *         description="Title of the post"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         example="This is the content of the post",
 *         description="Main content body of the post"
 *     ),
 *     @OA\Property(
 *         property="image_url",
 *         type="string",
 *         format="url",
 *         nullable=true,
 *         example="https://example.com/image.jpg",
 *         description="Optional image URL associated with the post"
 *     ),
 *     @OA\Property(
 *         property="scheduled_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         example="2025-05-25T12:00:00Z",
 *         description="Datetime when the post is scheduled to be published"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         enum={0,1,2,3},
 *         example=0,
 *         description="Post status: 0=draft, 1=scheduled, 2=published, 3=archived"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID of the user who owns the post"
 *     ),
 *     @OA\Property(
 *         property="published_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Datetime when the post was published"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Datetime when the post was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Datetime when the post was last updated"
 *     )
 * )
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'status',
        'scheduled_at',
        'user_id',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => PostStatusEnum::class,
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post published on platforms.
     *
     * @return BelongsToMany
     */
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform', 'post_id', 'platform_id');
    }
}
