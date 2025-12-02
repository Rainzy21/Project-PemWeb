<?php

namespace App\Controllers\Traits;

trait ManagesRoomOperations
{
    /**
     * Check if room has active bookings
     */
    protected function hasActiveBookings($bookingModel, int $roomId): bool
    {
        $result = $bookingModel->raw(
            "SELECT COUNT(*) as count FROM bookings 
             WHERE room_id = :room_id 
             AND status IN ('pending', 'confirmed', 'checked_in')",
            [':room_id' => $roomId]
        );

        return ($result[0]->count ?? 0) > 0;
    }

    /**
     * Get room booking history
     */
    protected function getRoomBookingHistory($bookingModel, int $roomId, int $limit = 10): array
    {
        return $bookingModel->raw(
            "SELECT b.*, u.name as guest_name
             FROM bookings b
             JOIN users u ON b.user_id = u.id
             WHERE b.room_id = :room_id
             ORDER BY b.created_at DESC
             LIMIT {$limit}",
            [':room_id' => $roomId]
        );
    }

    /**
     * Get room statistics
     */
    protected function getRoomStatistics($bookingModel, int $roomId): ?object
    {
        $result = $bookingModel->raw(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as total_revenue,
                SUM(DATEDIFF(check_out_date, check_in_date)) as total_nights
             FROM bookings
             WHERE room_id = :room_id
             AND status IN ('checked_in', 'checked_out')",
            [':room_id' => $roomId]
        );

        return $result[0] ?? null;
    }

    /**
     * Get rooms list stats
     */
    protected function getRoomsListStats($roomModel): array
    {
        return [
            'total' => $roomModel->count(),
            'available' => count($roomModel->getAvailable()),
            'standard' => count($roomModel->getStandard()),
            'deluxe' => count($roomModel->getDeluxe()),
            'suite' => count($roomModel->getSuite())
        ];
    }

    /**
     * Get filtered rooms
     */
    protected function getFilteredRoomsAdmin($roomModel, ?string $type, ?string $status): array
    {
        if ($type && $this->isValidRoomType($type)) {
            return $roomModel->getByType($type);
        }

        if ($status === 'available') {
            return $roomModel->getAvailable();
        }

        if ($status === 'occupied') {
            return $roomModel->raw("SELECT * FROM rooms WHERE is_available = 0");
        }

        return $roomModel->all();
    }
}