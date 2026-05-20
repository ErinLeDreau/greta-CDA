<?php

namespace App\Models;

use App\Models\Traits\GeneralTrait;
use App\Models\Traits\IdentifiableTrait;
use App\Models\Traits\TimestampableTrait;
use DateTime;

class Category
{
    use TimestampableTrait;
    use IdentifiableTrait;
    use GeneralTrait;

    public function __construct(int $id, array $materials = [], string $name = '', string $description = '', ?DateTime $createdAt = null, ?DateTime $updatedAt = null)
    {
        $this->initGeneralTrait($name, $description);
        $this->initIdentifiableTrait($id);
        $this->initTimestampableTrait($createdAt, $updatedAt);
        $this->materials = $materials;

    }

    /**
     * @var Material[]
     */
    private array $materials = [];

    /**
     * @return Material[]
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    /**
     * @param Material[] $materials
     * @return self
     */
    public function setMaterials(array $materials): self
    {
        $this->materials = $materials;
        return $this;
    }
    
}
