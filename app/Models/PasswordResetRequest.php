<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasPendingStatus;

class PasswordResetRequest extends Model
{
    use HasPendingStatus;

    protected string $table = 'password_reset_requests';
    protected array $fillable = ['user_id', 'email', 'status', 'processed_at'];

    /**
     * Buat request baru (cek duplikat otomatis)
     */
    public function createRequest(int $userId, string $email): bool
    {
        if ($this->hasPending('user_id', $userId)) {
            return true;
        }

        return (bool) $this->create([
            'user_id' => $userId,
            'email' => $email,
            'status' => 'pending'
        ]);
    }

    /**
     * Get pending requests dengan info user
     */
    public function getPendingWithUser(): array
    {
        return $this->db->query(
            "SELECT pr.*, u.name as user_name 
             FROM {$this->table} pr 
             JOIN users u ON pr.user_id = u.id 
             WHERE pr.status = 'pending' 
             ORDER BY pr.created_at DESC"
        )->resultSet();
    }

    /**
     * Get all requests dengan info user
     */
    public function getAllWithUser(): array
    {
        return $this->db->query(
            "SELECT pr.*, u.name as user_name 
             FROM {$this->table} pr 
             JOIN users u ON pr.user_id = u.id 
             ORDER BY pr.created_at DESC"
        )->resultSet();
    }

    /**
     * Count pending requests
     */
    public function countPending(): int
    {
        return (int) $this->db->query(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'"
        )->single()->count ?? 0;
    }
}