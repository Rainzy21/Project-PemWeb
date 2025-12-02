<?php

namespace App\Controllers\Traits;

trait CalculatesRevenue
{
    /**
     * Calculate total revenue from checked_out bookings
     */
    protected function calculateTotalRevenue($bookingModel): float
    {
        $bookings = $bookingModel->getByStatus('checked_out');
        
        return array_reduce($bookings, fn($total, $booking) => $total + $booking->total_price, 0);
    }

    /**
     * Calculate current month revenue
     */
    protected function calculateMonthlyRevenue($bookingModel): float
    {
        $result = $bookingModel->raw(
            "SELECT SUM(total_price) as revenue 
             FROM bookings 
             WHERE status = 'checked_out' 
             AND MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );

        return $result[0]->revenue ?? 0;
    }

    /**
     * Get monthly revenue for chart (last 12 months)
     */
    protected function getMonthlyRevenueChart($bookingModel): array
    {
        return $bookingModel->raw(
            "SELECT 
                MONTH(created_at) as month, 
                YEAR(created_at) as year, 
                SUM(total_price) as revenue,
                COUNT(*) as total_bookings
             FROM bookings 
             WHERE status = 'checked_out' 
             AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
             GROUP BY YEAR(created_at), MONTH(created_at) 
             ORDER BY year ASC, month ASC"
        );
    }

    /**
     * Get revenue report for date range
     */
    protected function getRevenueReport($bookingModel, string $startDate, string $endDate): ?object
    {
        $result = $bookingModel->raw(
            "SELECT 
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as realized_revenue,
                SUM(CASE WHEN status IN ('pending', 'confirmed', 'checked_in') THEN total_price ELSE 0 END) as potential_revenue,
                SUM(CASE WHEN status = 'cancelled' THEN total_price ELSE 0 END) as cancelled_revenue,
                COUNT(*) as total_bookings
             FROM bookings 
             WHERE DATE(created_at) BETWEEN :start AND :end",
            [':start' => $startDate, ':end' => $endDate]
        );

        return $result[0] ?? null;
    }
}