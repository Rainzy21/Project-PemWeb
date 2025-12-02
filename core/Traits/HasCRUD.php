<?php

namespace Core\Traits;

trait HasCRUD
{
    /**
     * Get all records
     */
    public function all(): array
    {
        return $this->db->query("SELECT * FROM {$this->table}")->resultSet();
    }

    /**
     * Find by ID
     */
    public function find(int $id): ?object
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->single();
    }

    /**
     * Create record
     */
    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $this->db->query("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");

        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }

        $this->db->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Update record
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        $set = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));

        $this->db->query("UPDATE {$this->table} SET {$set} WHERE id = :id");

        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Delete record
     */
    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->execute();
    }
}