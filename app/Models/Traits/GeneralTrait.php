<?php

namespace App\Models\Traits;

trait GeneralTrait
{
    private string $name;
    private string $description;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $name
     * @param string $description
     * @return void
     */
    public function initGeneralTrait(string $name = '', string $description = ''): void
    {
        $this->setName($name);
        $this->setDescription($description);
    }
}
