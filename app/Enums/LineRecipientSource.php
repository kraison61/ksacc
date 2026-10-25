<?php

namespace App\Enums;

enum LineRecipientSource: string
{
    case User = 'user';
    case Group = 'group';

    public function label(): string
    {
        return match ($this) {
            self::User => 'ผู้ใช้',
            self::Group => 'กลุ่ม',
        };
    }
}
