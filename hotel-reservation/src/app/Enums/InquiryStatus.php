<?php

namespace App\Enums;

enum InquiryStatus: int
{
    case Pending    = 1;
    case InProgress = 2;
    case Resolved   = 3;

    public function label(): string
    {
        return match ($this) {
            self::Pending    => '未対応',
            self::InProgress => '対応中',
            self::Resolved   => '対応完了',
        };
    }
}
