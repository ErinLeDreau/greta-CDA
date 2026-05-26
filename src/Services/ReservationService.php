<?php

namespace App\Services;

use App\DTO\ReservationDTO;
use App\Managers\RepositoryManager;
use App\Models\Reservation;
use App\Models\Enums\ReservationStatusEnum;
use App\Models\Material;
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
     * @param ReservationDTO $dto
     * @return Reservation
     * @throws Exception
     */
    public function createReservation(ReservationDTO $dto): Reservation
    {
        $this->validateReservationDTO($dto);

        $user = $this->repositoryManager->userRepository->findById($dto->userId);
        $material = $this->repositoryManager->materialRepository->findById($dto->materialId);

        if (!$user || !$material) {
            throw new Exception("User or Material not found");
        }

        $start = $this->parseDate($dto->startDate);
        $end = $this->parseDate($dto->endDate);

        $this->checkAvailability($material, $start, $end, null);

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

        return $this->repositoryManager->reservationRepository->create($reservation);
    }

    /**
     * @param int $reservationId
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function cancelReservation(int $reservationId, int $userId): void
    {
        $reservation = $this->repositoryManager->reservationRepository->findById($reservationId);
        $user = $this->repositoryManager->userRepository->findById($userId);

        if (!$reservation || !$user) {
            throw new Exception("Not found");
        }

        $now = new DateTime();
        $start = $reservation->getStartDate();

        if (!$user->isAdmin()) {
            if ($now >= $start) {
                throw new Exception("Already started");
            }

            if (($start->getTimestamp() - $now->getTimestamp()) < 86400) {
                throw new Exception("Too late");
            }
        }

        $reservation->setStatus(ReservationStatusEnum::CANCELLED);

        $this->repositoryManager->reservationRepository->update($reservation);
    }

    /**
     * @param int $reservationId
     * @param int $adminId
     * @return void
     * @throws Exception
     */
    public function completeReservation(int $reservationId, int $adminId): void
    {
        $reservation = $this->repositoryManager->reservationRepository->findById($reservationId);
        $admin = $this->repositoryManager->userRepository->findById($adminId);

        if (!$reservation || !$admin) {
            throw new Exception("Not found");
        }

        if (!$admin->isAdmin()) {
            throw new Exception("Unauthorized");
        }

        $reservation->setStatus(ReservationStatusEnum::COMPLETED);

        $this->repositoryManager->reservationRepository->update($reservation);
    }

    /**
     * @param int $reservationId
     * @param int $adminId
     * @return void
     * @throws Exception
     */
    public function deleteReservation(int $reservationId, int $adminId): void
    {
        $reservation = $this->repositoryManager->reservationRepository->findById($reservationId);
        $admin = $this->repositoryManager->userRepository->findById($adminId);

        if (!$reservation || !$admin) {
            throw new Exception("Not found");
        }

        if (!$admin->isAdmin()) {
            throw new Exception("Unauthorized");
        }

        $this->repositoryManager->reservationRepository->delete($reservationId);
    }

    /**
     * @param ReservationDTO $dto
     * @return Reservation
     * @throws Exception
     */
    public function updateReservation(ReservationDTO $dto): Reservation
    {
        if (!$dto->id) {
            throw new Exception("Reservation id required");
        }

        $this->validateReservationDTO($dto);

        $reservation = $this->repositoryManager->reservationRepository->findById($dto->id);

        if (!$reservation) {
            throw new Exception("Reservation not found");
        }

        $start = $this->parseDate($dto->startDate);
        $end = $this->parseDate($dto->endDate);

        $this->checkAvailability(
            $reservation->getMaterial(),
            $start,
            $end,
            $reservation->getId()
        );

        $reservation->setStartDate($start);
        $reservation->setEndDate($end);
        $reservation->setUpdatedAt(new DateTime());

        return $this->repositoryManager->reservationRepository->update($reservation);
    }

    /**
     * @param Material $material
     * @param DateTime $start
     * @param DateTime $end
     * @param int|null $excludeId
     * @throws Exception
     * @return void
     */
    private function checkAvailability(Material $material, DateTime $start, DateTime $end, ?int $excludeId): void
    {
        $reservations = $this->repositoryManager
            ->reservationRepository
            ->findActiveOverlapping(
                $material->getId(),
                $start,
                $end,
                $excludeId
            );

        $available = $material->getQuantity() - $material->getQuantityBroken();

        if (count($reservations) >= $available) {
            throw new Exception("No availability");
        }
    }

    /**
     * @param ReservationDTO $dto
     * @throws Exception
     * @return void
     */
    private function validateReservationDTO(ReservationDTO $dto): void
    {
        if (!$dto->userId || !$dto->materialId) {
            throw new Exception("Missing data");
        }
        
        $start = $this->parseDate($dto->startDate);
        $end = $this->parseDate($dto->endDate);

        if (!$start || !$end) {
            throw new Exception("Invalid date format");
        }

        if ($end <= $start) {
            throw new Exception("Invalid dates");
        }

        if (!$dto->status) {
            throw new Exception("Invalid status");
        }
    }

    private function parseDate(string $input): DateTime
    {
        $input = trim($input);
        $input = str_replace('/', '-', $input);

        return new DateTime($input);
    }
}