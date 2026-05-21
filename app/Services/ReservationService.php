<?php

namespace App\Services;

use App\Managers\RepositoryManager;
use App\Models\Reservation;
use App\Models\Enums\ReservationStatusEnum;
use DateTime;
use Exception;

class ReservationService
{
    private RepositoryManager $repositoryManager;

    /**
     * @param RepositoryManager $repositoryManager
     */
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
     * @return Reservation
     */
    public function createReservation(
        int $userId,
        int $materialId,
        DateTime $start,
        DateTime $end): Reservation 
    {

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

        $id = $this->repositoryManager->reservationRepository->create($reservation);

        $reservation->setId($id);
        return $reservation;
    }

    /**
     * @param int $reservationId
     * @param int $userId
     * @throws Exception
     * @return void
     */
    public function cancelReservation(int $reservationId, int $userId): void
    {
        $reservation = $this->repositoryManager->reservationRepository->findById($reservationId);
        $user = $this->repositoryManager->userRepository->findById($userId);

        if (!$reservation) {
            throw new Exception("Reservation not found");
        }

        if ($reservation->getUser()->getId() !== $userId || !$user->isAdmin()) {
            throw new Exception("Unauthorized");
        }

        $now = new DateTime();
        $diff = $now->diff($reservation->getStartDate());

        if ($diff->days < 1 && $now < $reservation->getStartDate()) {
            throw new Exception("Cannot cancel less than 24h before start");
        }

        $reservation->setStatus(ReservationStatusEnum::CANCELLED);

        $this->repositoryManager->reservationRepository->update($reservation);
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
        $material = $this->repositoryManager->materialRepository->findById($materialId);

        if (!$material) {
            throw new Exception("Material doesn't exist");
        }

        if (!$material->isAvailable()){
            throw new Exception("Material is not available");
        }
        $reservations = $this->repositoryManager->reservationRepository->findByMaterial($materialId);

        foreach ($reservations as $reservation) {

            $existingStart = $reservation->getStartDate();
            $existingEnd = $reservation->getEndDate();

            $isOverlap =
                $start < $existingEnd ||
                $end > $existingStart;

            if ($isOverlap) {
                throw new Exception("Material already reserved for this time slot");
            }
        }
    }
}