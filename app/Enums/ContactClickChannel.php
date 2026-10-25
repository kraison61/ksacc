<?php

namespace App\Enums;

enum ContactClickChannel: string
{
    case Phone = 'phone';
    case Line = 'line';

    public function label(): string
    {
        return match ($this) {
            self::Phone => 'โทร',
            self::Line => 'แอด LINE',
        };
    }
}
