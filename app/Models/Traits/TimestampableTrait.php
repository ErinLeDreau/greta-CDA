<?php

namespace App\Models\Traits;

use DateTime;

trait TimestampableTrait
{
    /** @var ?DateTime */
    private ?DateTime $createdAt = null;

    /** @var ?DateTime */
    private ?DateTime $updatedAt = null;

    /**
     * @return ?DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param ?DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return ?DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param ?DateTime $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param ?DateTime $createdAt
     * @param ?DateTime $updatedAt
     * @return void
     */
    public function initTimestampableTrait(?DateTime $createdAt = null, ?DateTime $updatedAt = null): void
    {
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    /**
     * @return DateTime
     */
    public function updateTime(): DateTime
    {
        $updatedAt = new DateTime();
        $this->setUpdatedAt($updatedAt);

        return $updatedAt;
    }
}
