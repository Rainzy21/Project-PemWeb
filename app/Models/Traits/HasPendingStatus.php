<?php

namespace App\Models\Traits;

trait HasPendingStatus
{
    /**
     * Cek apakah ada record pending untuk field tertentu
     */
    public function hasPending(string $column, mixed $value): bool
    {
        $result = $this->db->query(
            "SELECT id FROM {$this->table} WHERE {$column} = :value AND status = 'pending'"
        )->bind(':value', $value)->single();
        
        return $result !== null;
    }

    /**
     * Get semua record pending
     */
    public function getPending(): array
    {
        return $this->db->query(
            "SELECT * FROM {$this->table} WHERE status = 'pending' ORDER BY created_at DESC"
        )->resultSet();
    }

    /**
     * Count pending
     */
    public function countPending(): int
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'pending'"
        )->single();
        
        return (int) ($result->total ?? 0);
    }

    /**
     * Mark as completed
     */
    public function markCompleted(int $id): bool
    {
        return $this->update($id, [
            'status' => 'completed',
            'processed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark as rejected
     */
    public function markRejected(int $id): bool
    {
        return $this->update($id, [
            'status' => 'rejected',
            'processed_at' => date('Y-m-d H:i:s')
        ]);
    }
}