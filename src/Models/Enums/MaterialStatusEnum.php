<?php

namespace App\Models\Enums;

enum MaterialStatusEnum: string
{
    case AVAILABLE = 'available';
    case BROKEN = 'broken';
    case BORROWED = 'borrowed';
}
