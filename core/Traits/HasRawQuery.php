<?php

namespace Core\Traits;

trait HasRawQuery
{
    /**
     * Execute raw query - return multiple
     */
    public function raw(string $sql, array $params = []): array
    {
        $this->db->query($sql);

        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Execute raw query - return single
     */
    public function rawSingle(string $sql, array $params = []): ?object
    {
        $this->db->query($sql);

        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->single();
    }

    /**
     * Execute raw query - no return
     */
    public function rawExecute(string $sql, array $params = []): bool
    {
        $this->db->query($sql);

        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->execute();
    }

    /**
     * Check if record exists
     */
    public function exists(string $column, mixed $value): bool
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} = :value")
                           ->bind(':value', $value)
                           ->single();
        return ($result->count ?? 0) > 0;
    }

    /**
     * First record
     */
    public function first(): ?object
    {
        return $this->db->query("SELECT * FROM {$this->table} LIMIT 1")->single();
    }

    /**
     * Last record
     */
    public function last(): ?object
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1")->single();
    }
}