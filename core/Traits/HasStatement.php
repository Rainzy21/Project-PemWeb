<?php

namespace Core\Traits;

use PDO;
use PDOStatement;

trait HasStatement
{
    private ?PDOStatement $stmt = null;

    /**
     * Prepare SQL query
     */
    public function query(string $sql): self
    {
        $this->stmt = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * Bind value to parameter
     */
    public function bind(string $param, mixed $value, ?int $type = null): self
    {
        $type = $type ?? $this->detectType($value);
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Bind multiple values
     */
    public function bindMany(array $params): self
    {
        foreach ($params as $param => $value) {
            $this->bind($param, $value);
        }
        return $this;
    }

    /**
     * Detect PDO type from value
     */
    protected function detectType(mixed $value): int
    {
        return match (true) {
            is_int($value) => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            is_null($value) => PDO::PARAM_NULL,
            default => PDO::PARAM_STR
        };
    }

    /**
     * Execute prepared statement
     */
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    /**
     * Get row count
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }
}