<?php

namespace App\Models\Traits;

trait HasStatus
{
    /**
     * Status column name
     */
    protected function getStatusColumn(): string
    {
        return $this->statusColumn ?? 'status';
    }

    /**
     * Update status
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, [
            $this->getStatusColumn() => $status
        ]);
    }

    /**
     * Get by status
     */
    public function getByStatus(string $status): array
    {
        return $this->where($this->getStatusColumn(), $status);
    }

    /**
     * Count by status
     */
    public function countByStatus(string $status): int
    {
        return $this->countWhere($this->getStatusColumn(), $status);
    }

    /**
     * Check if has specific status
     */
    public function hasStatus(int $id, string $status): bool
    {
        $record = $this->find($id);
        $column = $this->getStatusColumn();
        
        return $record && ($record->{$column} ?? '') === $status;
    }

    /**
     * Get status counts
     */
    public function getStatusCounts(): array
    {
        $column = $this->getStatusColumn();
        $sql = "SELECT {$column}, COUNT(*) as total FROM {$this->table} GROUP BY {$column}";
        
        $results = $this->db->query($sql)->resultSet();
        
        $counts = [];
        foreach ($results as $row) {
            $counts[$row->{$column}] = $row->total;
        }
        
        return $counts;
    }
}