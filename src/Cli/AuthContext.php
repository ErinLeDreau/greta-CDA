<?php

namespace App\Cli;

use App\Models\User;

class AuthContext
{
    public ?User $currentUser = null;
}