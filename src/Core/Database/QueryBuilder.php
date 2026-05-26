<?php

namespace App\Core\Database;

class QueryBuilder
{

    private Database $database;
    private string $table = '';
    private array $where = [];
    private array $params = [];
    private array $orderBy = [];

    /**
     * @param Database $database
     * @return void
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $table
     * @return QueryBuilder
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return QueryBuilder
     */
    public function where(string $column, string $operator, mixed $value): self
    {
        $key = $column . count($this->params);

        $this->where[] = "$column $operator :$key";
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * @param string $column
     * @param string $direction
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $direction = strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            throw new \InvalidArgumentException("Invalid order direction: $direction");
        }

        $this->orderBy[] = "$column $direction";

        return $this;
    }

    /**
     * @return array|null
     */
    public function first(): ?array
    {
        $result = $this->get();
        return $result[0] ?? null;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        if ($this->where) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }
        return $this->database->fetchAll($sql, $this->params);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        $cols = array_keys($data);
        $keys = array_map(fn($c) => ":$c", $cols);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ")
                VALUES (" . implode(',', $keys) . ")";

        $this->database->execute($sql, $data);

        return $this->database->lastInsertId();
    }

    /**
     * @param array $data
     * @return int
     */
    public function update(array $data): int
    {
        $set = [];

        foreach ($data as $col => $value) {
            $set[] = "$col = :$col";
            $this->params[$col] = $value;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set);

        if ($this->where) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        return $this->database->executeUpdate($sql, $this->params);
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";

        if ($this->where) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        return $this->database->executeUpdate($sql, $this->params);
    }
}