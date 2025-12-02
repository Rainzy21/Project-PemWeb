<?php

namespace App\Controllers\Traits;

trait GeneratesReports
{
    /**
     * Get report date range from request
     */
    protected function getReportDateRange(): array
    {
        return [
            'start' => $_GET['start_date'] ?? date('Y-m-01'),
            'end' => $_GET['end_date'] ?? date('Y-m-d')
        ];
    }

    /**
     * Get occupancy report
     */
    protected function getOccupancyReport($bookingModel, $roomModel, string $startDate, string $endDate): array
    {
        $totalRooms = $roomModel->count();
        
        $days = (new \DateTime($startDate))->diff(new \DateTime($endDate))->days + 1;
        $totalRoomNights = $totalRooms * $days;

        $result = $bookingModel->raw(
            "SELECT SUM(DATEDIFF(check_out_date, check_in_date)) as occupied_nights
             FROM bookings 
             WHERE status IN ('checked_in', 'checked_out')
             AND check_in_date <= :end AND check_out_date >= :start",
            [':start' => $startDate, ':end' => $endDate]
        );

        $occupiedNights = $result[0]->occupied_nights ?? 0;
        $occupancyRate = $totalRoomNights > 0 
            ? round(($occupiedNights / $totalRoomNights) * 100, 2) 
            : 0;

        return [
            'total_rooms' => $totalRooms,
            'total_days' => $days,
            'total_room_nights' => $totalRoomNights,
            'occupied_nights' => $occupiedNights,
            'occupancy_rate' => $occupancyRate
        ];
    }

    /**
     * Get booking summary by status
     */
    protected function getBookingSummary($bookingModel, string $startDate, string $endDate): array
    {
        return $bookingModel->raw(
            "SELECT 
                status,
                COUNT(*) as count,
                SUM(total_price) as total_value
             FROM bookings 
             WHERE DATE(created_at) BETWEEN :start AND :end
             GROUP BY status",
            [':start' => $startDate, ':end' => $endDate]
        );
    }
}