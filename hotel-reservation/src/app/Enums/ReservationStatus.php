<?php

namespace App\Enums;

enum ReservationStatus: int
{
    case Confirmed  = 1;
    case Cancelled  = 2;
}
