<?php

namespace App\Enums;

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
