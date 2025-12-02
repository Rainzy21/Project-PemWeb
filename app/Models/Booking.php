<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasStatus;
use App\Models\Traits\Filterable;

class Booking extends Model
{
    use HasStatus, Filterable;

    protected string $table = 'bookings';
    protected array $fillable = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'total_price', 'status'];

    // Trait configurations
    protected string $statusColumn = 'status';
    protected array $filterable = ['status', 'user_id', 'room_id'];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get bookings by user
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Get all bookings with user and room details
     */
    public function getAllWithDetails(): array
    {
        $sql = "SELECT b.*, u.name as guest_name, u.email as guest_email, 
                       r.room_number, r.room_type, r.price_per_night
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                ORDER BY b.created_at DESC";
        
        return $this->db->query($sql)->resultSet();
    }

    /**
     * Get booking with details
     */
    public function getWithDetails(int $id): ?object
    {
        $sql = "SELECT b.*, u.name as guest_name, u.email as guest_email, u.phone as guest_phone,
                       r.room_number, r.room_type, r.price_per_night, r.description as room_description
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                WHERE b.id = :id";
        
        return $this->db->query($sql)
                        ->bind(':id', $id)
                        ->single();
    }

    /**
     * Get user bookings with room details
     */
    public function getUserBookingsWithDetails(int $userId): array
    {
        $sql = "SELECT b.*, r.room_number, r.room_type, r.price_per_night, r.image
                FROM {$this->table} b
                JOIN rooms r ON b.room_id = r.id
                WHERE b.user_id = :user_id
                ORDER BY b.created_at DESC";
        
        return $this->db->query($sql)
                        ->bind(':user_id', $userId)
                        ->resultSet();
    }

    /**
     * Get today's check-ins
     */
    public function getTodayCheckIns(): array
    {
        $today = date('Y-m-d');
        $sql = "SELECT b.*, u.name as guest_name, r.room_number
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                WHERE b.check_in_date = :today 
                AND b.status IN ('confirmed', 'checked_in')
                ORDER BY b.created_at DESC";
        
        return $this->db->query($sql)
                        ->bind(':today', $today)
                        ->resultSet();
    }

    /**
     * Get today's check-outs
     */
    public function getTodayCheckOuts(): array
    {
        $today = date('Y-m-d');
        $sql = "SELECT b.*, u.name as guest_name, r.room_number
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                WHERE b.check_out_date = :today 
                AND b.status = 'checked_in'
                ORDER BY b.created_at DESC";
        
        return $this->db->query($sql)
                        ->bind(':today', $today)
                        ->resultSet();
    }

    /**
     * Get pending bookings
     */
    public function getPending(): array
    {
        return $this->getByStatus(self::STATUS_PENDING);
    }

    /**
     * Get confirmed bookings
     */
    public function getConfirmed(): array
    {
        return $this->getByStatus(self::STATUS_CONFIRMED);
    }

    /**
     * Confirm booking
     */
    public function confirm(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CONFIRMED);
    }

    /**
     * Check in
     */
    public function checkIn(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CHECKED_IN);
    }

    /**
     * Check out
     */
    public function checkOut(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CHECKED_OUT);
    }

    /**
     * Cancel booking
     */
    public function cancel(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CANCELLED);
    }

    /**
     * Calculate total price
     */
    public function calculateTotalPrice(float $pricePerNight, string $checkIn, string $checkOut): float
    {
        $nights = $this->calculateNights($checkIn, $checkOut);
        return $pricePerNight * $nights;
    }

    /**
     * Calculate nights
     */
    public function calculateNights(string $checkIn, string $checkOut): int
    {
        $checkInDate = new \DateTime($checkIn);
        $checkOutDate = new \DateTime($checkOut);
        
        return $checkOutDate->diff($checkInDate)->days;
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue(): float
    {
        $sql = "SELECT COALESCE(SUM(total_price), 0) as total FROM {$this->table} 
                WHERE status IN ('confirmed', 'checked_in', 'checked_out')";
        
        $result = $this->db->query($sql)->single();
        return (float) ($result->total ?? 0);
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue(int $year, int $month): float
    {
        $sql = "SELECT COALESCE(SUM(total_price), 0) as total FROM {$this->table} 
                WHERE YEAR(created_at) = :year 
                AND MONTH(created_at) = :month
                AND status IN ('confirmed', 'checked_in', 'checked_out')";
        
        $result = $this->db->query($sql)
                           ->bind(':year', $year)
                           ->bind(':month', $month)
                           ->single();
        
        return (float) ($result->total ?? 0);
    }

    /**
     * Get revenue by date range
     */
    public function getRevenueByDateRange(string $startDate, string $endDate): ?object
    {
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN status IN ('confirmed', 'checked_in', 'checked_out') THEN total_price ELSE 0 END), 0) as realized,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN total_price ELSE 0 END), 0) as potential,
                    COALESCE(SUM(CASE WHEN status = 'cancelled' THEN total_price ELSE 0 END), 0) as cancelled,
                    COUNT(*) as total_bookings
                FROM {$this->table}
                WHERE created_at BETWEEN :start_date AND :end_date";
        
        return $this->db->query($sql)
                        ->bind(':start_date', $startDate)
                        ->bind(':end_date', $endDate . ' 23:59:59')
                        ->single();
    }

    /**
     * Get booking statistics
     */
    public function getStats(): array
    {
        return [
            'total' => $this->count(),
            'pending' => $this->countByStatus(self::STATUS_PENDING),
            'confirmed' => $this->countByStatus(self::STATUS_CONFIRMED),
            'checked_in' => $this->countByStatus(self::STATUS_CHECKED_IN),
            'checked_out' => $this->countByStatus(self::STATUS_CHECKED_OUT),
            'cancelled' => $this->countByStatus(self::STATUS_CANCELLED),
            'today_check_ins' => count($this->getTodayCheckIns()),
            'today_check_outs' => count($this->getTodayCheckOuts()),
            'total_revenue' => $this->getTotalRevenue()
        ];
    }

    /**
     * Get recent bookings
     */
    public function getRecent(int $limit = 10): array
    {
        $sql = "SELECT b.*, u.name as guest_name, r.room_number
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                ORDER BY b.created_at DESC
                LIMIT :limit";
        
        return $this->db->query($sql)
                        ->bind(':limit', $limit)
                        ->resultSet();
    }

    /**
     * Get all statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CHECKED_IN,
            self::STATUS_CHECKED_OUT,
            self::STATUS_CANCELLED
        ];
    }
}