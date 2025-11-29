<?php

namespace App\Models\Enums;

enum RegisterType: int
{
    case FREE = 0;
    case DOWNLOAD = 1;
    case CONTRIBUTE  = 2;

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Free',
            self::DOWNLOAD => 'Download',
            self::CONTRIBUTE  => 'Contribute',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FREE => 'text-yellow-600 bg-yellow-100',
            self::DOWNLOAD => 'text-green-600 bg-green-100',
            self::CONTRIBUTE  => 'text-red-600 bg-red-100',
        };
    }
}
