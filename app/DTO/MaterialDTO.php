<?php

namespace App\DTO;

class MaterialDTO
{
    public ?int $id;
    public int $categoryId;
    public string $name;
    public string $description;
    public int $quantity;
    public string $status;
    public int $quantityBorrowed;
    public int $quantityBroken;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * @param int|null $id
     * @param int $categoryId
     * @param string $name
     * @param string $description
     * @param int $quantity
     * @param string $status
     * @param int $quantityBorrowed
     * @param int $quantityBroken
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        ?int $id,
        int $categoryId,
        string $name,
        string $description,
        int $quantity,
        string $status,
        int $quantityBorrowed = 0,
        int $quantityBroken = 0,
        ?string $createdAt = null,
        ?string $updatedAt = null
    )
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->status = $status;
        $this->quantityBorrowed = $quantityBorrowed;
        $this->quantityBroken = $quantityBroken;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}