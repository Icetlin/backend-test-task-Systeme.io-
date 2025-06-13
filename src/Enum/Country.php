<?php

namespace App\Enum;

enum Country: string
{
    case GERMANY = 'DE';
    case ITALY   = 'IT';
    case FRANCE  = 'FR';
    case GREECE  = 'GR';

    public function getTaxRate(): int
    {
        return match($this) {
            self::GERMANY => 19,
            self::ITALY   => 22,
            self::FRANCE  => 20,
            self::GREECE  => 24,
        };
    }

    public static function tryFromCode(string $code): ?self
    {
        return self::tryFrom(strtoupper($code));
    }
}