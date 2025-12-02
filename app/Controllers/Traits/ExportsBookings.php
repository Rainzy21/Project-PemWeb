<?php

namespace App\Controllers\Traits;

trait ExportsBookings
{
    /**
     * Export bookings to CSV
     */
    protected function exportBookingsToCsv($bookingModel, array $filters): void
    {
        $bookings = $bookingModel->getAllWithDetails();

        // Apply filters
        $bookings = $this->filterByStatus($bookings, $filters['status']);
        $bookings = $this->filterByDateRange($bookings, $filters['start_date'], $filters['end_date']);

        $filename = 'bookings_' . date('Y-m-d') . '.csv';
        $headers = ['ID', 'Guest', 'Room', 'Check In', 'Check Out', 'Total', 'Status', 'Created'];

        $data = array_map(fn($booking) => [
            $booking->id,
            $booking->guest_name,
            $booking->room_number,
            $booking->check_in_date,
            $booking->check_out_date,
            $booking->total_price,
            $booking->status,
            $booking->created_at
        ], $bookings);

        $this->exportToCsv($filename, $headers, $data);
    }
}