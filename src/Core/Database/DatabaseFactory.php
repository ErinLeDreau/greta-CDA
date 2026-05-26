<?php

namespace App\Core\Database;

class DatabaseFactory
{
    public static function create(): Database
    {
        $path = __DIR__. '/../../../database/database.sqlite';
        return new Database($path);
    }
}