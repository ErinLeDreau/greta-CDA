<?php

namespace App\Cli\Admin;

use App\Cli\AuthContext;
use App\Managers\RepositoryManager;
use App\Models\Enums\MaterialStatusEnum;
use App\Services\MaterialService;

class MaterialMenu
{
    private MaterialService $materialService;
    private RepositoryManager $repositoryManager;
    private AuthContext $authContext;

    public function __construct(RepositoryManager $repositoryManager, AuthContext $authContext)
    {
        $this->materialService = new MaterialService($repositoryManager);
        $this->repositoryManager = $repositoryManager;
        $this->authContext = $authContext;
    }

    public function displayMenu(): void
    {
        while (true) {
            echo PHP_EOL;
            echo "=== MATERIAL MENU ===" . PHP_EOL;
            echo "1 - Create (Admin)" . PHP_EOL;
            echo "2 - Delete (Admin)" . PHP_EOL;
            echo "3 - List" . PHP_EOL;
            echo "4 - Exit" . PHP_EOL;

            $choice = readline("Choice: ");

            switch ($choice) {
                case '1': $this->create(); break;
                case '2': $this->delete(); break;
                case '3': $this->list(); break;
                case '4': return;
            }
        }
    }

    private function create(): void
    {
        try {
            $name = readline("Name: ");
            $description = readline("Description: ");
            $categoryId = readline("Category ID: ");
            $quantity = readline("Quantity: ");

            echo "Status (1=AVAILABLE): ";
            $statusChoice = readline();

            $statusMap = [
                '1' => MaterialStatusEnum::AVAILABLE,
            ];

            $dto = new \App\DTO\MaterialDTO(
                null,
                (int)$categoryId,
                $name,
                $description,
                (int)$quantity,
                $statusMap[$statusChoice],
                0,
                null,
                null
            );

            $this->materialService->createMaterial($dto);

            echo "Created" . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function delete(): void
    {
        $id = readline("ID: ");

        if (!is_numeric($id)) return;

        if (readline("Are you sure? (y/n): ") !== 'y') return;

        $this->materialService->deleteMaterial((int)$id);
    }

    private function list(): void
    {
        $materials = $this->repositoryManager->materialRepository->findAll();

        foreach ($materials as $m) {
            echo $m->getId() . " - " . $m->getName() . PHP_EOL;
        }
    }
}