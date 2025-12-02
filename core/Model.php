<?php

namespace Core;

class Model
{
    protected $db;
    protected $table;
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all records
     */
    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->resultSet();
    }

    /**
     * Find by ID
     */
    public function find($id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->single();
    }

    /**
     * Find by any column
     */
    public function findBy($column, $value)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->single();
    }

    /**
     * Get all by column
     */
    public function where($column, $value)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->resultSet();
    }

    /**
     * Create record
     */
    public function create(array $data)
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
    public function update($id, array $data)
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
    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->execute();
    }

    /**
     * Filter fillable
     */
    protected function filterFillable(array $data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Count records
     */
    public function count()
    {
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}")->single();
        return $result->total ?? 0;
    }

    /**
     * Raw query
     */
    public function raw($sql, array $params = [])
    {
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        return $this->db->resultSet();
    }
}