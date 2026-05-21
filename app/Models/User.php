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

    /**
     * @param integer $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $passwordHash
     * @param UserRoleEnum $role
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     */
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

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return UserRoleEnum
     */
    public function getRole(): UserRoleEnum
    {
        return $this->role;
    }

    /**
     * @param UserRoleEnum $role
     */
    public function setRole(UserRoleEnum $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN;
    }

}