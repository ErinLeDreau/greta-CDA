<?php

namespace App\Models;

use App\Models\Enums\MaterialStatusEnum;
use App\Models\Traits\GeneralTrait;
use App\Models\Traits\IdentifiableTrait;
use App\Models\Traits\TimestampableTrait;
use DateTime;

class Material
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use GeneralTrait;

    public Category $category;

    /**
     * @var Reservation[]
     */
    public array $reservations = [];

    private int $quantity = 0;
    private MaterialStatusEnum $status = MaterialStatusEnum::AVAILABLE;
    private int $quantityBorrowed = 0;
    private int $quantityBroken = 0;

    public function __construct(int $id, Category $category, array $reservations = [], string $name = '', string $description = '', ?DateTime $createdAt = null, ?DateTime $updatedAt = null)
    {
        $this->category = $category;
        $this->reservations = $reservations;

        $this->initGeneralTrait($name, $description);
        $this->initIdentifiableTrait($id);
        $this->initTimestampableTrait($createdAt, $updatedAt);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getStatus(): MaterialStatusEnum
    {
        return $this->status;
    }

    public function setStatus(MaterialStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getQuantityBorrowed(): int
    {
        return $this->quantityBorrowed;
    }

    public function setQuantityBorrowed(int $quantityBorrowed): void
    {
        $this->quantityBorrowed = $quantityBorrowed;
    }

    public function getQuantityBroken(): int
    {
        return $this->quantityBroken;
    }

    public function setQuantityBroken(int $quantityBroken): void
    {
        $this->quantityBroken = $quantityBroken;
    }
}
