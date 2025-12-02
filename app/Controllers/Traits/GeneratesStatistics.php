<?php

namespace App\Controllers\Traits;

trait GeneratesStatistics
{
    /**
     * Get main dashboard statistics
     */
    protected function getDashboardStats($userModel, $roomModel, $bookingModel): array
    {
        return [
            'total_users' => $userModel->count(),
            'total_guests' => count($userModel->getGuests()),
            'total_admins' => count($userModel->getAdmins()),
            'total_rooms' => $roomModel->count(),
            'available_rooms' => count($roomModel->getAvailable()),
            'occupied_rooms' => $roomModel->count() - count($roomModel->getAvailable()),
            'total_bookings' => $bookingModel->count(),
            'pending_bookings' => count($bookingModel->getPending()),
            'confirmed_bookings' => count($bookingModel->getConfirmed()),
            'today_checkins' => count($bookingModel->getTodayCheckIns()),
            'today_checkouts' => count($bookingModel->getTodayCheckOuts()),
            'total_revenue' => $this->calculateTotalRevenue($bookingModel),
            'monthly_revenue' => $this->calculateMonthlyRevenue($bookingModel)
        ];
    }

    /**
     * Get room statistics by type
     */
    protected function getRoomStats($roomModel): array
    {
        return [
            'standard' => count($roomModel->getStandard()),
            'deluxe' => count($roomModel->getDeluxe()),
            'suite' => count($roomModel->getSuite())
        ];
    }

    /**
     * Get booking statistics by status
     */
    protected function getBookingStats($bookingModel): array
    {
        $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
        
        return array_combine(
            $statuses,
            array_map(fn($status) => count($bookingModel->getByStatus($status)), $statuses)
        );
    }

    /**
     * Get booking trends for last 30 days
     */
    protected function getBookingTrends($bookingModel): array
    {
        return $bookingModel->raw(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as total
             FROM bookings 
             WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at)
             ORDER BY date ASC"
        );
    }

    /**
     * Get room type popularity
     */
    protected function getRoomTypePopularity($bookingModel): array
    {
        return $bookingModel->raw(
            "SELECT 
                r.room_type,
                COUNT(b.id) as total_bookings,
                SUM(b.total_price) as total_revenue
             FROM bookings b
             JOIN rooms r ON b.room_id = r.id
             WHERE b.status IN ('confirmed', 'checked_in', 'checked_out')
             GROUP BY r.room_type
             ORDER BY total_bookings DESC"
        );
    }
}