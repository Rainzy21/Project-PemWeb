<?php

namespace App\Models\Traits;

trait Searchable
{
    /**
     * Get searchable columns
     */
    protected function getSearchableColumns(): array
    {
        return $this->searchable ?? [];
    }

    /**
     * Search records
     */
    public function search(string $keyword): array
    {
        $columns = $this->getSearchableColumns();
        
        if (empty($columns) || empty($keyword)) {
            return $this->all();
        }

        $conditions = array_map(fn($col) => "{$col} LIKE :keyword", $columns);
        $whereClause = implode(' OR ', $conditions);
        
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        
        return $this->db->query($sql)
                        ->bind(':keyword', "%{$keyword}%")
                        ->resultSet();
    }

    /**
     * Search with pagination
     */
    public function searchPaginated(string $keyword, int $limit, int $offset = 0): array
    {
        $columns = $this->getSearchableColumns();
        
        if (empty($columns) || empty($keyword)) {
            return $this->limit($limit, $offset);
        }

        $conditions = array_map(fn($col) => "{$col} LIKE :keyword", $columns);
        $whereClause = implode(' OR ', $conditions);
        
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} LIMIT :limit OFFSET :offset";
        
        return $this->db->query($sql)
                        ->bind(':keyword', "%{$keyword}%")
                        ->bind(':limit', $limit)
                        ->bind(':offset', $offset)
                        ->resultSet();
    }

    /**
     * Count search results
     */
    public function countSearch(string $keyword): int
    {
        $columns = $this->getSearchableColumns();
        
        if (empty($columns) || empty($keyword)) {
            return $this->count();
        }

        $conditions = array_map(fn($col) => "{$col} LIKE :keyword", $columns);
        $whereClause = implode(' OR ', $conditions);
        
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereClause}";
        
        $result = $this->db->query($sql)
                           ->bind(':keyword', "%{$keyword}%")
                           ->single();
        
        return $result->total ?? 0;
    }
}