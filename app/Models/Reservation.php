<?php

namespace App\Models;

use App\Models\Enums\ReservationStatusEnum;
use App\Models\Traits\IdentifiableTrait;
use App\Models\Traits\TimestampableTrait;
use DateTime;

class Reservation
{
    use TimestampableTrait;
    use IdentifiableTrait;

    private DateTime $startDate;
    private DateTime $endDate;
    private ReservationStatusEnum $status;
    private User $user;
    private Material $material;

    public function __construct(
        int $id,
        Material $material,
        DateTime $startDate,
        DateTime $endDate,
        ReservationStatusEnum $status,
        User $user,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->initIdentifiableTrait($id);
        $this->material = $material;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->user = $user;

        $this->initTimestampableTrait($createdAt, $updatedAt);
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getStatus(): ReservationStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ReservationStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }
}
