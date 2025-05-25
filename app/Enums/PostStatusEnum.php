<?php

namespace App\Enums;

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
