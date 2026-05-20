<?php

namespace App\Models\Traits;

trait IdentifiableTrait
{
    /**
     * @var int
     */
    protected int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function initIdentifiableTrait(int $id): void
    {
        $this->setId($id);
    }
}
