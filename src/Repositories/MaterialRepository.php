<?php

namespace App\Repositories;

use App\Core\Database\Database;
use App\Models\Material;
use App\Models\Enums\MaterialStatusEnum;
use DateTime;

class MaterialRepository
{
    private Database $database;
    private CategoryRepository $categoryRepository;
    protected string $table = "materials";

    /**
     * @param Database $database
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(Database $database, CategoryRepository $categoryRepository)
    {
        $this->database = $database;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param int $id
     * @return Material|null
     */
    public function findById(int $id): ?Material
    {
        $data = $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $id)
            ->first();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @param int $categoryId
     * @return Material[]|null
     */
    public function findByCategoryId(int $categoryId): ?array
    {
        $rows = $this->database->builder()
            ->table($this->table)
            ->where('category_id', '=', $categoryId)
            ->get() 
        ;

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $rows
        );
    }

    /**
     * @return Material[]
     */
    public function findAll(): array
    {
        $rows = $this->database->builder()
            ->table($this->table)
            ->get();

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $rows
        );
    }

    /**
     * @param Material $material
     * @return Material
     */
    public function create(Material $material): Material
    {
        $id = $this->database->builder()
            ->table($this->table)
            ->insert([
                'category_id' => $material->getCategory()->getId(),
                'name' => $material->getName(),
                'description' => $material->getDescription(),
                'quantity' => $material->getQuantity(),
                'status' => $material->getStatus()->value,
                'quantity_broken' => $material->getQuantityBroken(),
                'created_at' => $material->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $material->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);

        $material->setId($id);

        return $material;
    }

    /**
     * @param Material $material
     * @return Material
     */
    public function update(Material $material): Material
    {
        $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $material->getId())
            ->update([
                'category_id' => $material->getCategory()->getId(),
                'name' => $material->getName(),
                'description' => $material->getDescription(),
                'quantity' => $material->getQuantity(),
                'status' => $material->getStatus()->value,
                'quantity_broken' => $material->getQuantityBroken(),
                'updated_at' => $material->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);

        return $material;
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * @param int $categoryId
     * @return int
     */
    public function deleteByCategory(int $categoryId): int
    {
        return $this->database->builder()
            ->table($this->table)
            ->where('category_id', '=', $categoryId)
            ->delete();
    }

    /**
     * @param array $data
     * @return Material
     */
    private function hydrate(array $data): Material
    {
        $category = $this->categoryRepository
            ->findById((int) $data['category_id']);

        $material = new Material(
            (int) $data['id'],
            $category,
            $data['name'],
            $data['description'],
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );

        $material->setQuantity((int) $data['quantity']);
        $material->setStatus(MaterialStatusEnum::from($data['status']));
        $material->setQuantityBroken((int) $data['quantity_broken']);

        return $material;
    }
}