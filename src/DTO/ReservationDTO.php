<?php

namespace App\DTO;

use App\Models\Enums\ReservationStatusEnum;
use App\Models\Reservation;

class ReservationDTO
{
    public ?int $id;
    public int $userId;
    public int $materialId;
    public string $startDate;
    public string $endDate;
    public ReservationStatusEnum $status;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * 
     * @param int|null $id
     * @param int $userId
     * @param int $materialId
     * @param string $startDate
     * @param string $endDate
     * @param ReservationStatusEnum $status
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        ?int $id,
        int $userId,
        int $materialId,
        string $startDate,
        string $endDate,
        ReservationStatusEnum $status,
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