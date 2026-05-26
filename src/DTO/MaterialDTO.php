<?php

namespace App\DTO;

use App\Models\Enums\MaterialStatusEnum;

class MaterialDTO
{
    public ?int $id;
    public int $categoryId;
    public string $name;
    public string $description;
    public int $quantity;
    public MaterialStatusEnum $status;
    public int $quantityBroken;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * @param int|null $id
     * @param int $categoryId
     * @param string $name
     * @param string $description
     * @param int $quantity
     * @param MaterialStatusEnum $status
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
        MaterialStatusEnum $status,
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
        $this->quantityBroken = $quantityBroken;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}