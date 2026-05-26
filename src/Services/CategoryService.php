<?php

namespace App\Services;

use App\DTO\CategoryDTO;
use App\Managers\RepositoryManager;
use App\Models\Category;
use DateTime;
use Exception;

class CategoryService
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
     * @param CategoryDTO $dto
     * @return Category
     * @throws Exception
     */
    public function createCategory(CategoryDTO $dto): Category
    {
        $this->validateCategoryDTO($dto);

        $category = new Category(
            0,
            $dto->name,
            $dto->description,
            new DateTime(),
            new DateTime()
        );

        return $this->repositoryManager->categoryRepository->create($category);
    }

    /**
     * @param CategoryDTO $dto
     * @return Category
     * @throws Exception
     */
    public function updateCategory(CategoryDTO $dto): Category
    {
        if (!$dto->id) {
            throw new Exception("Category id required");
        }

        $this->validateCategoryDTO($dto);

        $category = $this->repositoryManager->categoryRepository->findById($dto->id);

        if (!$category) {
            throw new Exception("Category not found");
        }

        $category->setName($dto->name);
        $category->setDescription($dto->description);
        $category->setUpdatedAt(new DateTime());

        return $this->repositoryManager->categoryRepository->update($category);
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteCategory(int $id): void
    {
        $category = $this->repositoryManager->categoryRepository->findById($id);
        if (!$category) {
            throw new Exception("Category not found");
        }
        $materials = $this->repositoryManager->materialRepository->findByCategoryId($id);
        foreach ($materials as $material) {
            $this->repositoryManager->reservationRepository->deleteByMaterialId($material->getId());
            $this->repositoryManager->materialRepository->delete($material->getId());
        }
        $this->repositoryManager->categoryRepository->delete($id);
    }

    /**
     * @param CategoryDTO $dto
     * @return void
     * @throws Exception
     */
    private function validateCategoryDTO(CategoryDTO $dto): void
    {
        if (empty(trim($dto->name))) {
            throw new Exception("Name required");
        }
    }
}