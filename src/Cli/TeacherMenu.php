<?php

namespace App\Cli;

use App\Managers\RepositoryManager;
use App\Services\ReservationService;

class TeacherMenu
{
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;
    private ReservationService $reservationService;
    public function __construct(RepositoryManager $repositoryManager,AuthContext $authContext) 
    {
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
        $this->reservationService = new ReservationService($repositoryManager);
    }

    public function handle(): void
    {
        while (true) {

            echo PHP_EOL . "=== TEACHER ===" . PHP_EOL;
            echo "1. My reservations" . PHP_EOL;
            echo "2. Create reservation" . PHP_EOL;
            echo "3. Cancel reservation" . PHP_EOL;
            echo "0. Back" . PHP_EOL;

            $choice = readline("Choice: ");

            switch ($choice) {
                case "1":
                    $this->myReservations();
                    break;

                case "2":
                    $this->create();
                    break;

                case "3":
                    $this->cancel();
                    break;

                case "0":
                    return;
            }
        }
    }

    private function myReservations(): void
    {
        $user = $this->authContext->currentUser;

        $reservations = $this->repositoryManager->reservationRepository
            ->findByUser($user->getId());

        foreach ($reservations as $r) {

            echo "ID: " . $r->getId() . PHP_EOL;

            echo "Material: " 
                . $r->getMaterial()->getName()
                . PHP_EOL;

            echo "Start: " 
                . $r->getStartDate()->format('Y-m-d H:i')
                . PHP_EOL;

            echo "End: " 
                . $r->getEndDate()->format('Y-m-d H:i')
                . PHP_EOL;

            echo "Status: " 
                . $r->getStatus()->value
                . PHP_EOL;

            echo "------------------------" . PHP_EOL;
        }
    }

    private function create(): void
    {
        try {
            $materialId = readline("Material ID: ");
            $start = readline("Start date (dd/mm/yyyy hh:mm): ");
            $end = readline("End date (dd/mm/yyyy hh:mm): ");

            $dto = new \App\DTO\ReservationDTO(
                null,
                $this->authContext->currentUser->getId(),
                (int)$materialId,
                $start,
                $end,
                \App\Models\Enums\ReservationStatusEnum::ACTIVE,
                null,
                null
            );
            $reservation = $this->reservationService->createReservation($dto);

            echo "Reservation created (#" . $reservation->getId() . ")" . PHP_EOL;

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    private function cancel(): void
    {
        try {
            $id = readline("Reservation ID: ");

            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("Invalid ID");
            }

            $confirm = readline("Cancel this reservation? (y/n): ");

            if (strtolower($confirm) !== 'y') {
                echo "Cancelled" . PHP_EOL;
                return;
            }

            $this->reservationService->cancelReservation(
                (int)$id,
                $this->authContext->currentUser->getId()
            );

            echo "Reservation cancelled" . PHP_EOL;

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }
}