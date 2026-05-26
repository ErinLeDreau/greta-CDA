<?php

namespace App\Cli\Admin;

use App\Managers\RepositoryManager;
use App\Services\CategoryService;
class CategoryMenu
{
    private CategoryService $categoryService;
    private RepositoryManager $repositoryManager;

    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->categoryService = new CategoryService($repositoryManager);
        $this->repositoryManager = $repositoryManager;
    }

    public function displayMenu(): void
    {
        while (true) {
            echo PHP_EOL;
            echo "=== CATEGORY MENU ===" . PHP_EOL;
            echo "1 - Create" . PHP_EOL;
            echo "2 - Delete" . PHP_EOL;
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

            $dto = new \App\DTO\CategoryDTO(
                null,
                $name,
                $description,
                null,
                null
            );

            $this->categoryService->createCategory($dto);

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

        $this->categoryService->deleteCategory((int)$id);

        echo "Deleted" . PHP_EOL;
    }

    private function list(): void
    {
        $categories = $this->repositoryManager->categoryRepository->findAll();

        foreach ($categories as $c) {
            echo $c->getId() . " - " . $c->getName() . PHP_EOL;
        }
    }
}