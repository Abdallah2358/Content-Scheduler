<?php

namespace App\Enums;

use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'PlatformTypeEnum',
    type: 'integer',
    enum: [0, 1, 2, 3],
    description: 'Enumeration of social media platforms',
    title: 'PlatformTypeEnum',
    properties: [
        new OA\Property(property: 'TWITTER', type: 'integer', enum: [0], description: 'Twitter platform'),
        new OA\Property(property: 'INSTAGRAM', type: 'integer', enum: [1], description: 'Instagram platform'),
        new OA\Property(property: 'LINKEDIN', type: 'integer', enum: [2], description: 'LinkedIn platform'),
        new OA\Property(property: 'FACEBOOK', type: 'integer', enum: [3], description: 'Facebook platform'),
    ]
    
)]
enum PlatformTypeEnum: int
{
    case TWITTER = 0;
    case INSTAGRAM = 1;
    case LINKEDIN = 2;
    case FACEBOOK = 3;
    public function label(): string
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::INSTAGRAM => 'Instagram',
            self::LINKEDIN => 'LinkedIn',
            self::FACEBOOK => 'Facebook',
        };
    }
}
