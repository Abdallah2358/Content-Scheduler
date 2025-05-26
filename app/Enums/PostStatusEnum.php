<?php

namespace App\Enums;
use OpenApi\Attributes as OA;
#[OA\Schema(
    type: 'integer',
    enum: [0, 1, 2],
    example: 0,
    description: 'Post status: 0 = Draft, 1 = Scheduled, 2 = Published'
)]
enum PostStatusEnum: int
{
    case DRAFT = 0;
    case SCHEDULED = 1;
    case PUBLISHED = 2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SCHEDULED => 'Scheduled',
            self::PUBLISHED => 'Published',
        };
    }
}
