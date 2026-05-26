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

    private Category $category;
    private int $quantity = 0;
    private MaterialStatusEnum $status = MaterialStatusEnum::AVAILABLE;
    private int $quantityBroken = 0;

    /**
     * @param int $id
     * @param Category $category
     * @param string $name
     * @param string $description
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     */
    public function __construct(
        int $id, 
        Category $category, 
        string $name = '', 
        string $description = '', 
        ?DateTime $createdAt = null, 
        ?DateTime $updatedAt = null
        )
    {
        $this->category = $category;

        $this->initGeneralTrait($name, $description);
        $this->initIdentifiableTrait($id);
        $this->initTimestampableTrait($createdAt, $updatedAt);
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
    
    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return MaterialStatusEnum
     */
    public function getStatus(): MaterialStatusEnum
    {
        return $this->status;
    }

    /**
     * @param MaterialStatusEnum $status
     */
    public function setStatus(MaterialStatusEnum $status): void
    {
        $this->status = $status;
    }
    /**
     * @return int
     */
    public function getQuantityBroken(): int
    {
        return $this->quantityBroken;
    }

    /**
     * @param int $quantityBroken
     */
    public function setQuantityBroken(int $quantityBroken): void
    {
        $this->quantityBroken = $quantityBroken;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        if($this->getStatus() !== MaterialStatusEnum::AVAILABLE) {
            return false;
        }

        $qtyAvailable = $this->getQuantity() - $this->getQuantityBroken();

        if($qtyAvailable > 0){
            return true;
        }

        return false;
    }
    
}
