<?php

namespace App\Enums;

enum CalendarAvailability
{
    case Available;   // 空きあり（2枠以上）
    case Limited;     // 残りわずか（1枠）
    case Unavailable; // 空きなし

    public static function fromCount(int $count): self
    {
        return match (true) {
            $count >= 2 => self::Available,
            $count === 1 => self::Limited,
            default      => self::Unavailable,
        };
    }
}
