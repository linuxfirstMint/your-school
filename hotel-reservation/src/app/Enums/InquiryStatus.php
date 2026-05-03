<?php

namespace App\Enums;

enum InquiryStatus: int
{
    case Unread = 1;
    case Read   = 2;

    public function label(): string
    {
        return match ($this) {
            self::Unread => '問い合わせ中',
            self::Read   => '完了',
        };
    }
}
