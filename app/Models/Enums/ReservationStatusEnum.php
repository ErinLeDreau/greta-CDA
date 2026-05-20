<?php

namespace App\Models\Enums;

enum ReservationStatusEnum: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}
