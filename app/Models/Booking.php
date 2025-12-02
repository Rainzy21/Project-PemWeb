<?php

namespace App\Models;

use Core\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'total_price', 'status'];

    /**
     * Create booking with price calculation
     */
    public function createBooking($userId, $roomId, $checkIn, $checkOut, $pricePerNight)
    {
        $nights = $this->calculateNights($checkIn, $checkOut);
        $totalPrice = $nights * $pricePerNight;

        return $this->create([
            'user_id' => $userId,
            'room_id' => $roomId,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);
    }

    /**
     * Calculate nights between dates
     */
    public function calculateNights($checkIn, $checkOut)
    {
        $date1 = new \DateTime($checkIn);
        $date2 = new \DateTime($checkOut);
        return $date1->diff($date2)->days;
    }

    /**
     * Get bookings by user
     */
    public function getByUser($userId)
    {
        return $this->db->query("SELECT b.*, r.room_number, r.room_type, r.image 
                                 FROM {$this->table} b
                                 JOIN rooms r ON b.room_id = r.id
                                 WHERE b.user_id = :user_id
                                 ORDER BY b.created_at DESC")
                        ->bind(':user_id', $userId)
                        ->resultSet();
    }

    /**
     * Get bookings by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status);
    }

    /**
     * Get pending bookings
     */
    public function getPending()
    {
        return $this->getByStatus('pending');
    }

    /**
     * Get confirmed bookings
     */
    public function getConfirmed()
    {
        return $this->getByStatus('confirmed');
    }

    /**
     * Update booking status
     */
    public function updateStatus($id, $status)
    {
        $validStatus = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
        
        if (!in_array($status, $validStatus)) {
            return false;
        }

        return $this->update($id, ['status' => $status]);
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        return $this->updateStatus($id, 'confirmed');
    }

    /**
     * Check in
     */
    public function checkIn($id)
    {
        return $this->updateStatus($id, 'checked_in');
    }

    /**
     * Check out
     */
    public function checkOut($id)
    {
        return $this->updateStatus($id, 'checked_out');
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        return $this->updateStatus($id, 'cancelled');
    }

    /**
     * Get booking with details
     */
    public function getWithDetails($id)
    {
        return $this->db->query("SELECT b.*, 
                                        u.name as guest_name, u.email as guest_email, u.phone as guest_phone,
                                        r.room_number, r.room_type, r.price_per_night, r.image as room_image
                                 FROM {$this->table} b
                                 JOIN users u ON b.user_id = u.id
                                 JOIN rooms r ON b.room_id = r.id
                                 WHERE b.id = :id")
                        ->bind(':id', $id)
                        ->single();
    }

    /**
     * Get all bookings with details (for admin)
     */
    public function getAllWithDetails()
    {
        return $this->db->query("SELECT b.*, 
                                        u.name as guest_name, u.email as guest_email,
                                        r.room_number, r.room_type
                                 FROM {$this->table} b
                                 JOIN users u ON b.user_id = u.id
                                 JOIN rooms r ON b.room_id = r.id
                                 ORDER BY b.created_at DESC")
                        ->resultSet();
    }

    /**
     * Get today's check-ins
     */
    public function getTodayCheckIns()
    {
        $today = date('Y-m-d');
        return $this->db->query("SELECT b.*, u.name as guest_name, r.room_number
                                 FROM {$this->table} b
                                 JOIN users u ON b.user_id = u.id
                                 JOIN rooms r ON b.room_id = r.id
                                 WHERE b.check_in_date = :today
                                 AND b.status = 'confirmed'")
                        ->bind(':today', $today)
                        ->resultSet();
    }

    /**
     * Get today's check-outs
     */
    public function getTodayCheckOuts()
    {
        $today = date('Y-m-d');
        return $this->db->query("SELECT b.*, u.name as guest_name, r.room_number
                                 FROM {$this->table} b
                                 JOIN users u ON b.user_id = u.id
                                 JOIN rooms r ON b.room_id = r.id
                                 WHERE b.check_out_date = :today
                                 AND b.status = 'checked_in'")
                        ->bind(':today', $today)
                        ->resultSet();
    }
}