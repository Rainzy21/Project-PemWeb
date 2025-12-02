<?php

namespace Core\Traits;

trait HasFetch
{
    /**
     * Fetch all results
     */
    public function resultSet(): array
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Fetch single result
     */
    public function single(): ?object
    {
        $this->execute();
        $result = $this->stmt->fetch();
        return $result ?: null;
    }

    /**
     * Fetch as array
     */
    public function resultArray(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Fetch single as array
     */
    public function singleArray(): ?array
    {
        $this->execute();
        $result = $this->stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Fetch single column
     */
    public function column(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId(): int
    {
        return (int) $this->dbh->lastInsertId();
    }
}