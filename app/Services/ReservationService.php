<?php

namespace App\Services;

use App\DTO\ReservationDTO;
use App\Managers\RepositoryManager;
use App\Models\Reservation;
use App\Models\Enums\ReservationStatusEnum;
use DateTime;
use Exception;

class ReservationService
{
    private RepositoryManager $repositoryManager;

    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param int $userId
     * @param int $materialId
     * @param DateTime $start
     * @param DateTime $end
     * @throws Exception
     * @return ReservationDTO
     */
    public function createReservation(
        int $userId,
        int $materialId,
        DateTime $start,
        DateTime $end
    ): ReservationDTO {

        if ($end <= $start) {
            throw new Exception("End date must be after start date");
        }

        $user = $this->repositoryManager->userRepository->findById($userId);
        $material = $this->repositoryManager->materialRepository->findById($materialId);

        if (!$user || !$material) {
            throw new Exception("User or Material not found");
        }

        $this->checkConflicts($materialId, $start, $end);

        if ($material->getQuantity() <= 0) {
            throw new Exception("Material not available");
        }

        $reservation = new Reservation(
            0,
            $user,
            $material,
            $start,
            $end,
            ReservationStatusEnum::ACTIVE,
            new DateTime(),
            new DateTime()
        );

        $reservation = $this->repositoryManager
            ->reservationRepository
            ->create($reservation);

        return $this->toReservationDTO($reservation);
    }

    /**
     * @param int $reservationId
     * @param int $userId
     * @throws Exception
     * @return void
     */
    public function cancelReservation(int $reservationId, int $userId): void
    {
        $reservation = $this->repositoryManager
            ->reservationRepository
            ->findById($reservationId);

        $user = $this->repositoryManager
            ->userRepository
            ->findById($userId);

        if (!$reservation) {
            throw new Exception("Reservation not found");
        }

        if (!$user) {
            throw new Exception("User not found");
        }

        $now = new DateTime();
        $start = $reservation->getStartDate();
        $isAdmin = $user->isAdmin();

        if (!$isAdmin) {

            if ($now >= $start) {
                throw new Exception("Cannot cancel a reservation that has already started");
            }

            $diffSeconds = $start->getTimestamp() - $now->getTimestamp();

            if ($diffSeconds < 86400) {
                throw new Exception("Cannot cancel less than 24h before start");
            }
        }

        $reservation->setStatus(ReservationStatusEnum::CANCELLED);

        $this->repositoryManager
            ->reservationRepository
            ->update($reservation);
    }

    /**
     * @param int $materialId
     * @param DateTime $start
     * @param DateTime $end
     * @throws Exception
     * @return void
     */
    private function checkConflicts(int $materialId, DateTime $start, DateTime $end): void
    {
        $reservations = $this->repositoryManager
            ->reservationRepository
            ->findByMaterial($materialId);

        foreach ($reservations as $reservation) {

            $existingStart = $reservation->getStartDate();
            $existingEnd = $reservation->getEndDate();

            $isOverlap =
                $start < $existingEnd
                && $end > $existingStart;

            if ($isOverlap) {
                throw new Exception("Material already reserved for this time slot");
            }
        }
    }

    /**
     * @param Reservation $r
     * @return ReservationDTO
     */
    private function toReservationDTO(Reservation $r): ReservationDTO
    {
        return new ReservationDTO(
            $r->getId(),
            $r->getUser()->getId(),
            $r->getMaterial()->getId(),
            $r->getStartDate()->format('Y-m-d H:i:s'),
            $r->getEndDate()->format('Y-m-d H:i:s'),
            $r->getStatus()->value,
            $r->getCreatedAt()->format('Y-m-d H:i:s'),
            $r->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
}