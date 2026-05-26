<?php

namespace App\Services;

use App\DTO\MaterialDTO;
use App\Managers\RepositoryManager;
use App\Models\Material;
use App\Models\Enums\MaterialStatusEnum;
use DateTime;
use Exception;

class MaterialService
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
     * @param MaterialDTO $dto
     * @return Material
     * @throws Exception
     */
    public function createMaterial(MaterialDTO $dto): Material
    {
        $this->validateMaterialDTO($dto);

        $category = $this->repositoryManager->categoryRepository->findById($dto->categoryId);

        if (!$category) {
            throw new Exception("Category not found");
        }

        if (!$dto->status) {
            throw new Exception("Invalid status");
        }

        $material = new Material(
            0,
            $category,
            $dto->name,
            $dto->description,
            new DateTime(),
            new DateTime()
        );

        $material->setQuantity($dto->quantity);
        $material->setStatus($dto->status);
        $material->setQuantityBroken($dto->quantityBroken);

        return $this->repositoryManager->materialRepository->create($material);
    }

    /**
     * @param MaterialDTO $dto
     * @return Material
     * @throws Exception
     */
    public function updateMaterial(MaterialDTO $dto): Material
    {
        if (!$dto->id) {
            throw new Exception("Material id required");
        }

        $this->validateMaterialDTO($dto);

        $material = $this->repositoryManager->materialRepository->findById($dto->id);

        if (!$material) {
            throw new Exception("Material not found");
        }

        $category = $this->repositoryManager->categoryRepository->findById($dto->categoryId);

        if (!$category) {
            throw new Exception("Category not found");
        }

        if (!$dto->status) {
            throw new Exception("Invalid status");
        }

        $material->setCategory($category);
        $material->setName($dto->name);
        $material->setDescription($dto->description);
        $material->setQuantity($dto->quantity);
        $material->setStatus($dto->status);
        $material->setQuantityBroken($dto->quantityBroken);
        $material->setUpdatedAt(new DateTime());

        return $this->repositoryManager->materialRepository->update($material);
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteMaterial(int $id): void
    {
        $material = $this->repositoryManager->materialRepository->findById($id);

        if (!$material) {
            throw new Exception("Material not found");
        }
        $this->repositoryManager->reservationRepository->deleteByMaterialId($id);
        $this->repositoryManager->materialRepository->delete($id);
    }

    /**
     * @param MaterialDTO $dto
     * @return void
     * @throws Exception
     */
    private function validateMaterialDTO(MaterialDTO $dto): void
    {
        if (empty(trim($dto->name))) {
            throw new Exception("Name required");
        }

        if ($dto->quantity < 0) {
            throw new Exception("Invalid quantity");
        }

        if (!$dto->status) {
            throw new Exception("Invalid status");
        }
    }
}