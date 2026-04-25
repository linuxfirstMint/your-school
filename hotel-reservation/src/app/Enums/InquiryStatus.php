<?php

namespace App\Enums;

enum InquiryStatus: int
{
    case Unread = 1;
    case Read   = 2;
}
