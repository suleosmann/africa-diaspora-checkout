<?php

namespace App\Models\Enums;

enum TransactionStatus: int
{
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED  = -1;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SUCCESS => 'Success',
            self::FAILED  => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'text-yellow-600 bg-yellow-100',
            self::SUCCESS => 'text-green-600 bg-green-100',
            self::FAILED  => 'text-red-600 bg-red-100',
        };
    }
}
