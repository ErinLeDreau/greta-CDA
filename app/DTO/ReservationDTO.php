<?php

namespace App\DTO;

class ReservationDTO
{
    public ?int $id;
    public int $userId;
    public int $materialId;
    public string $startDate;
    public string $endDate;
    public string $status;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * 
     * @param int|null $id
     * @param int $userId
     * @param int $materialId
     * @param string $startDate
     * @param string $endDate
     * @param string $status
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        ?int $id,
        int $userId,
        int $materialId,
        string $startDate,
        string $endDate,
        string $status,
        ?string $createdAt = null,
        ?string $updatedAt = null) 
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->materialId = $materialId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}