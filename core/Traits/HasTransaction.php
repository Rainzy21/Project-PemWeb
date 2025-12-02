<?php

namespace Core\Traits;

trait HasTransaction
{
    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->dbh->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->dbh->rollBack();
    }

    /**
     * Check if in transaction
     */
    public function inTransaction(): bool
    {
        return $this->dbh->inTransaction();
    }

    /**
     * Execute callback in transaction
     */
    public function transaction(callable $callback): mixed
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}