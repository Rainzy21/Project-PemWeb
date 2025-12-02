<?php

namespace Core\Traits;

trait HasQuery
{
    /**
     * Find by any column (single)
     */
    public function findBy(string $column, mixed $value): ?object
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->single();
    }

    /**
     * Get all by column (multiple)
     */
    public function where(string $column, mixed $value): array
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->resultSet();
    }

    /**
     * Where with multiple conditions
     */
    public function whereMany(array $conditions): array
    {
        $clauses = [];
        $bindings = [];

        foreach ($conditions as $column => $value) {
            $clauses[] = "{$column} = :{$column}";
            $bindings[":{$column}"] = $value;
        }

        $whereClause = implode(' AND ', $clauses);
        $this->db->query("SELECT * FROM {$this->table} WHERE {$whereClause}");

        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Where IN clause
     */
    public function whereIn(string $column, array $values): array
    {
        if (empty($values)) {
            return [];
        }

        $placeholders = [];
        foreach ($values as $i => $value) {
            $placeholders[] = ":val{$i}";
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} IN (" . implode(',', $placeholders) . ")";
        $this->db->query($sql);

        foreach ($values as $i => $value) {
            $this->db->bind(":val{$i}", $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Order by
     */
    public function orderBy(string $column, string $direction = 'ASC'): array
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$column} {$direction}")
                        ->resultSet();
    }

    /**
     * Limit records
     */
    public function limit(int $limit, int $offset = 0): array
    {
        return $this->db->query("SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset")
                        ->bind(':limit', $limit)
                        ->bind(':offset', $offset)
                        ->resultSet();
    }
}