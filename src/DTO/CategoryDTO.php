<?php

namespace App\DTO;

class CategoryDTO
{
    public ?int $id;
    public string $name;
    public string $description;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $description
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        ?int $id,
        string $name,
        string $description,
        ?string $createdAt = null,
        ?string $updatedAt = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}