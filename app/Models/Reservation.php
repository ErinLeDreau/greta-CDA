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

    /**
     * @param int $id
     * @param User $user
     * @param Material $material
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param ReservationStatusEnum $status
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     */
    public function __construct(
        int $id,
        User $user,
        Material $material,
        DateTime $startDate,
        DateTime $endDate,
        ReservationStatusEnum $status,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->user = $user;
        $this->material = $material;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;

        $this->initIdentifiableTrait($id);
        $this->initTimestampableTrait($createdAt, $updatedAt);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Material
     */
    public function getMaterial(): Material
    {
        return $this->material;
    }

    /**
     * @param Material $material
     */
    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }
    
    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return ReservationStatusEnum
     */
    public function getStatus(): ReservationStatusEnum
    {
        return $this->status;
    }

    /**
     * @param ReservationStatusEnum $status
     */
    public function setStatus(ReservationStatusEnum $status): void
    {
        $this->status = $status;
    }
    
}
