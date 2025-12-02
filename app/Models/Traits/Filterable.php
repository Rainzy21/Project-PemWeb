<?php

namespace App\Models\Traits;

trait Filterable
{
    /**
     * Get filterable columns
     */
    protected function getFilterableColumns(): array
    {
        return $this->filterable ?? [];
    }

    /**
     * Filter by single column
     */
    public function filterBy(string $column, mixed $value): array
    {
        if (!in_array($column, $this->getFilterableColumns())) {
            return $this->all();
        }

        return $this->where($column, $value);
    }

    /**
     * Filter by multiple columns
     */
    public function filterByMany(array $filters): array
    {
        $allowedFilters = array_intersect_key(
            $filters, 
            array_flip($this->getFilterableColumns())
        );

        if (empty($allowedFilters)) {
            return $this->all();
        }

        return $this->whereMany($allowedFilters);
    }

    /**
     * Filter with price range
     */
    public function filterByPriceRange(float $minPrice, float $maxPrice, string $priceColumn = 'price_per_night'): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$priceColumn} BETWEEN :min AND :max";
        
        return $this->db->query($sql)
                        ->bind(':min', $minPrice)
                        ->bind(':max', $maxPrice)
                        ->resultSet();
    }

    /**
     * Filter with sorting
     */
    public function filterWithSort(array $filters, string $sortColumn, string $sortDirection = 'ASC'): array
    {
        $allowedFilters = array_intersect_key(
            $filters, 
            array_flip($this->getFilterableColumns())
        );

        $sortDirection = strtoupper($sortDirection) === 'DESC' ? 'DESC' : 'ASC';

        if (empty($allowedFilters)) {
            return $this->orderBy($sortColumn, $sortDirection);
        }

        $conditions = [];
        foreach ($allowedFilters as $column => $value) {
            $conditions[] = "{$column} = :{$column}";
        }

        $whereClause = implode(' AND ', $conditions);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} ORDER BY {$sortColumn} {$sortDirection}";

        $this->db->query($sql);
        foreach ($allowedFilters as $column => $value) {
            $this->db->bind(":{$column}", $value);
        }

        return $this->db->resultSet();
    }
}