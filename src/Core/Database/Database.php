<?php

namespace App\Core\Database;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private PDO $pdo;

    /**
     * @param string $dbPath
     */
    public function __construct(string $dbPath)
    {
        try {
            $this->pdo = new PDO("sqlite:$dbPath");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return new QueryBuilder($this);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement|false
     */
    public function execute(string $sql, array $params = []): ?PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function executeUpdate(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}