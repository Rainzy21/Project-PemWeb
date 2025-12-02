<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasImage;
use App\Models\Traits\Searchable;
use App\Models\Traits\Filterable;

class Room extends Model
{
    use HasImage, Searchable, Filterable;

    protected string $table = 'rooms';
    protected array $fillable = ['room_number', 'room_type', 'price_per_night', 'description', 'image', 'is_available'];

    // Trait configurations
    protected string $imageColumn = 'image';
    protected string $uploadDir = 'uploads/rooms';
    protected array $searchable = ['room_number', 'room_type', 'description'];
    protected array $filterable = ['room_type', 'is_available'];

    /**
     * Get available rooms
     */
    public function getAvailable(): array
    {
        return $this->where('is_available', 1);
    }

    /**
     * Get rooms by type
     */
    public function getByType(string $type): array
    {
        return $this->where('room_type', $type);
    }

    /**
     * Get standard rooms
     */
    public function getStandard(): array
    {
        return $this->getByType('standard');
    }

    /**
     * Get deluxe rooms
     */
    public function getDeluxe(): array
    {
        return $this->getByType('deluxe');
    }

    /**
     * Get suite rooms
     */
    public function getSuite(): array
    {
        return $this->getByType('suite');
    }

    /**
     * Find by room number
     */
    public function findByRoomNumber(string $roomNumber): ?object
    {
        return $this->findBy('room_number', $roomNumber);
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability(int $id): bool
    {
        $room = $this->find($id);
        
        if (!$room) {
            return false;
        }

        $newStatus = $room->is_available ? 0 : 1;
        return $this->update($id, ['is_available' => $newStatus]);
    }

    /**
     * Set availability
     */
    public function setAvailability(int $id, bool $available): bool
    {
        return $this->update($id, ['is_available' => $available ? 1 : 0]);
    }

    /**
     * Check if room available for dates
     */
    public function isAvailableForDates(int $roomId, string $checkIn, string $checkOut): bool
    {
        $sql = "SELECT COUNT(*) as total FROM bookings 
                WHERE room_id = :room_id 
                AND status NOT IN ('cancelled', 'checked_out')
                AND (
                    (check_in_date <= :check_in1 AND check_out_date > :check_in2)
                    OR (check_in_date < :check_out1 AND check_out_date >= :check_out2)
                    OR (check_in_date >= :check_in3 AND check_out_date <= :check_out3)
                )";
        
        $result = $this->db->query($sql)
                           ->bind(':room_id', $roomId)
                           ->bind(':check_in1', $checkIn)
                           ->bind(':check_in2', $checkIn)
                           ->bind(':check_out1', $checkOut)
                           ->bind(':check_out2', $checkOut)
                           ->bind(':check_in3', $checkIn)
                           ->bind(':check_out3', $checkOut)
                           ->single();
        
        return ($result->total ?? 0) == 0;
    }

    /**
     * Get room statistics
     */
    public function getStats(): array
    {
        return [
            'total' => $this->count(),
            'available' => $this->countWhere('is_available', 1),
            'unavailable' => $this->countWhere('is_available', 0),
            'standard' => $this->countWhere('room_type', 'standard'),
            'deluxe' => $this->countWhere('room_type', 'deluxe'),
            'suite' => $this->countWhere('room_type', 'suite')
        ];
    }

    /**
     * Get all room types
     */
    public function getTypes(): array
    {
        $sql = "SELECT DISTINCT room_type FROM {$this->table} ORDER BY room_type";
        return $this->db->query($sql)->column();
    }

    /**
     * Get available rooms for dates
     */
    public function getAvailableForDates(string $checkIn, string $checkOut): array
    {
        $sql = "SELECT r.* FROM {$this->table} r 
                WHERE r.is_available = 1 
                AND r.id NOT IN (
                    SELECT b.room_id FROM bookings b 
                    WHERE b.status NOT IN ('cancelled', 'checked_out')
                    AND (
                        (b.check_in_date <= :check_in1 AND b.check_out_date > :check_in2)
                        OR (b.check_in_date < :check_out1 AND b.check_out_date >= :check_out2)
                        OR (b.check_in_date >= :check_in3 AND b.check_out_date <= :check_out3)
                    )
                )";
        
        return $this->db->query($sql)
                        ->bind(':check_in1', $checkIn)
                        ->bind(':check_in2', $checkIn)
                        ->bind(':check_out1', $checkOut)
                        ->bind(':check_out2', $checkOut)
                        ->bind(':check_in3', $checkIn)
                        ->bind(':check_out3', $checkOut)
                        ->resultSet();
    }
}


