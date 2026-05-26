<?php

namespace App\Models\Enums;

enum ReservationStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case CANCELLED = 'CANCELLED';
    case COMPLETED = 'COMPLETED';
}
