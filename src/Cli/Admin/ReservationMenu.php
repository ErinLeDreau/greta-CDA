<?php

namespace App\Cli\Admin;

use App\Cli\AuthContext;
use App\Managers\RepositoryManager;
use App\Services\ReservationService;

class ReservationMenu
{
    private ReservationService $reservationService;
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;

    public function __construct(RepositoryManager $repositoryManager, AuthContext $authContext)
    {
        $this->reservationService = new ReservationService($repositoryManager);
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
    }

    public function displayMenu(): void
    {
        while (true) {
            echo PHP_EOL;
            echo "=== RESERVATION MENU ===" . PHP_EOL;
            echo "1 - Create Reservation" . PHP_EOL;
            echo "2 - Cancel Reservation" . PHP_EOL;
            echo "3 - End Reservation" . PHP_EOL;
            echo "4 - List" . PHP_EOL;
            echo "5 - Exit" . PHP_EOL;

            $choice = readline("Choice: ");

            switch ($choice) {
                case '1': $this->create(); break;
                case '2': $this->cancel(); break;
                case '3': $this->end(); break;
                case '4': $this->list(); break;
                case '5': return;
            }
        }
    }

    private function create(): void
    {
        try {
            $userId = readline("User ID: ");
            $materialId = readline("Material ID: ");
            $start = readline("Start date (dd/mm/yyyy hh:mm): ");
            $end = readline("End date (dd/mm/yyyy hh:mm): ");

            echo "Status (1=ACTIVE,2=CANCELLED,3=COMPLETED): ";
            $statusChoice = readline();

            $statusMap = [
                '1' => \App\Models\Enums\ReservationStatusEnum::ACTIVE,
                '2' => \App\Models\Enums\ReservationStatusEnum::CANCELLED,
                '3' => \App\Models\Enums\ReservationStatusEnum::COMPLETED,
            ];

            $dto = new \App\DTO\ReservationDTO(
                null,
                (int)$userId,
                (int)$materialId,
                $start,
                $end,
                $statusMap[$statusChoice],
                null,
                null
            );

            $this->reservationService->createReservation($dto);

            echo "Created" . PHP_EOL;

        } catch (\Exception $e) {
            echo $e->getMessage();
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

    private function end(): void
    {
        try {
            $id = readline("Reservation ID: ");

            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("Invalid ID");
            }

            $confirm = readline("End this reservation? (y/n): ");

            if (strtolower($confirm) !== 'y') {
                echo "Ended" . PHP_EOL;
                return;
            }

            $this->reservationService->completeReservation(
                (int)$id,
                $this->authContext->currentUser->getId()
            );

            echo "Reservation ended" . PHP_EOL;

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    private function list(): void
    {
        $reservations = $this->repositoryManager->reservationRepository->findAll();

        foreach ($reservations as $r) {
            echo $r->getId()
                . " U:" . $r->getUser()->getEmail()
                . " M:" . $r->getMaterial()->getName()
                . " S:" . $r->getStatus()->value
                . PHP_EOL;
        }
    }
}