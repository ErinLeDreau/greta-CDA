<?php

namespace App\Models;

use App\Models\Enums\UserRoleEnum;
use App\Models\Traits\IdentifiableTrait;
use App\Models\Traits\TimestampableTrait;
use DateTime;

class User
{
    
    use IdentifiableTrait;
    use TimestampableTrait;

    private string $firstName;
    private string $lastName;
    private string $email;
    private string $passwordHash;
    private UserRoleEnum $role;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $passwordHash,
        UserRoleEnum $role,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    )
    {
        $this->initIdentifiableTrait($id);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;

        $this->initTimestampableTrait($createdAt, $updatedAt);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRole(): UserRoleEnum
    {
        return $this->role;
    }

    public function setRole(UserRoleEnum $role): void
    {
        $this->role = $role;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN;
    }

}