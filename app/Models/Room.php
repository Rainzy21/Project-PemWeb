<?php

namespace App\Models;

use Core\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = ['room_number', 'room_type', 'price_per_night', 'description', 'image', 'is_available'];

    /**
     * Get available rooms
     */
    public function getAvailable()
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE is_available = 1")
                        ->resultSet();
    }

    /**
     * Get rooms by type
     */
    public function getByType($type)
    {
        return $this->where('room_type', $type);
    }

    /**
     * Get standard rooms
     */
    public function getStandard()
    {
        return $this->getByType('standard');
    }

    /**
     * Get deluxe rooms
     */
    public function getDeluxe()
    {
        return $this->getByType('deluxe');
    }

    /**
     * Get suite rooms
     */
    public function getSuite()
    {
        return $this->getByType('suite');
    }

    /**
     * Set room availability
     */
    public function setAvailability($id, $isAvailable)
    {
        return $this->update($id, ['is_available' => $isAvailable ? 1 : 0]);
    }

    /**
     * Find by room number
     */
    public function findByRoomNumber($roomNumber)
    {
        return $this->findBy('room_number', $roomNumber);
    }

    /**
     * Get rooms by price range
     */
    public function getByPriceRange($minPrice, $maxPrice)
    {
        return $this->db->query("SELECT * FROM {$this->table} 
                                 WHERE price_per_night BETWEEN :min AND :max 
                                 AND is_available = 1
                                 ORDER BY price_per_night ASC")
                        ->bind(':min', $minPrice)
                        ->bind(':max', $maxPrice)
                        ->resultSet();
    }

    /**
     * Check if room is available for dates
     */
    public function isAvailableForDates($roomId, $checkIn, $checkOut)
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM bookings 
                                    WHERE room_id = :room_id 
                                    AND status NOT IN ('cancelled', 'checked_out')
                                    AND (
                                        (check_in_date <= :check_in AND check_out_date > :check_in)
                                        OR (check_in_date < :check_out AND check_out_date >= :check_out)
                                        OR (check_in_date >= :check_in AND check_out_date <= :check_out)
                                    )")
                           ->bind(':room_id', $roomId)
                           ->bind(':check_in', $checkIn)
                           ->bind(':check_out', $checkOut)
                           ->single();
        
        return $result->count == 0;
    }
}


