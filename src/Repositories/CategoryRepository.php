<?php

namespace App\Repositories;

use App\Core\Database\Database;
use App\Models\Category;
use DateTime;

class CategoryRepository
{
    private Database $database;
    protected string $table = "categories";

    /**
     * @param Database $database
     */
    public function __construct(Database $database) {
        $this->database = $database;
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        $data = $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $id)
            ->first();
        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        $rows = $this->database->builder()
            ->table($this->table)
            ->get();

        return array_map(fn($category) => $this->hydrate($category), $rows);
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function create(Category $category): Category
    {
        $id = $this->database->builder()
            ->table($this->table)
            ->insert([
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'created_at' => $category->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $category->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);
        
        $category->setId($id);
        return $category;
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function update(Category $category): Category
    {
        $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $category->getId())
            ->update([
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'updated_at' => $category->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);
        
        return $category;
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
     * @param array $data
     * @return Category
     */
    private function hydrate(array $data): Category
    {
        return new Category(
            (int) $data['id'],
            $data['name'],
            $data['description'],
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );
    }
}