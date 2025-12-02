<?php

namespace Core\Traits;

trait HasAggregate
{
    /**
     * Count all records
     */
    public function count(): int
    {
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}")->single();
        return $result->total ?? 0;
    }

    /**
     * Count with condition
     */
    public function countWhere(string $column, mixed $value): int
    {
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE {$column} = :value")
                           ->bind(':value', $value)
                           ->single();
        return $result->total ?? 0;
    }

    /**
     * Sum column
     */
    public function sum(string $column): float
    {
        $result = $this->db->query("SELECT SUM({$column}) as total FROM {$this->table}")->single();
        return $result->total ?? 0;
    }

    /**
     * Sum with condition
     */
    public function sumWhere(string $sumColumn, string $whereColumn, mixed $value): float
    {
        $result = $this->db->query("SELECT SUM({$sumColumn}) as total FROM {$this->table} WHERE {$whereColumn} = :value")
                           ->bind(':value', $value)
                           ->single();
        return $result->total ?? 0;
    }

    /**
     * Average
     */
    public function avg(string $column): float
    {
        $result = $this->db->query("SELECT AVG({$column}) as average FROM {$this->table}")->single();
        return $result->average ?? 0;
    }

    /**
     * Max value
     */
    public function max(string $column): mixed
    {
        $result = $this->db->query("SELECT MAX({$column}) as maximum FROM {$this->table}")->single();
        return $result->maximum ?? null;
    }

    /**
     * Min value
     */
    public function min(string $column): mixed
    {
        $result = $this->db->query("SELECT MIN({$column}) as minimum FROM {$this->table}")->single();
        return $result->minimum ?? null;
    }
}