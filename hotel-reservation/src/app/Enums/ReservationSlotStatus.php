<?php

namespace App\Enums;

enum ReservationSlotStatus: int
{
    case Available = 1;
    case Booked    = 2;
}
