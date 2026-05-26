<?php

require_once 'vendor/autoload.php';

use App\Core\Database\DatabaseFactory;

$database = DatabaseFactory::create();

$passwordHash = password_hash("admin", PASSWORD_BCRYPT);

$sql = "
INSERT INTO users (
    firstname,
    lastname,
    email,
    password_hash,
    role,
    created_at,
    updated_at
)
VALUES (
    'Admin',
    'Root',
    'admin@admin.com',
    :password,
    'ADMIN',
    datetime('now'),
    datetime('now')
)
";

$stmt = $database
    ->getConnection()
    ->prepare($sql);

$stmt->execute([
    'password' => $passwordHash
]);

echo "Admin created" . PHP_EOL;