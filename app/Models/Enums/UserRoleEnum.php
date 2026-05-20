<?php

namespace App\Models\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
}
