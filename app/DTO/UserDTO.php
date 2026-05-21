<?php

namespace App\DTO;

class UserDTO
{
    public ?int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public ?string $password;
    public string $role;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * @param int|null $id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string|null $password
     * @param string $role
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        ?int $id,
        string $firstname,
        string $lastname,
        string $email,
        ?string $password,
        string $role,
        ?string $createdAt = null,
        ?string $updatedAt = null
    )
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}