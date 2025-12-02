<?php

namespace App\Controllers\Traits;

trait FiltersBookings
{
    /**
     * Get filtered bookings
     */
    protected function getFilteredBookings($bookingModel, ?string $status): array
    {
        if ($status && $this->isValidStatus($status)) {
            return $bookingModel->getByStatus($status);
        }

        return $bookingModel->getAllWithDetails();
    }

    /**
     * Filter bookings by date range
     */
    protected function filterByDateRange(array $bookings, ?string $startDate, ?string $endDate): array
    {
        if (!$startDate || !$endDate) {
            return $bookings;
        }

        return array_filter($bookings, function ($booking) use ($startDate, $endDate) {
            return $booking->check_in_date >= $startDate && $booking->check_in_date <= $endDate;
        });
    }

    /**
     * Filter bookings by status
     */
    protected function filterByStatus(array $bookings, ?string $status): array
    {
        if (!$status) {
            return $bookings;
        }

        return array_filter($bookings, fn($b) => $b->status === $status);
    }

    /**
     * Get booking filter params
     */
    protected function getBookingFilterParams(): array
    {
        return [
            'status' => $_GET['status'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null
        ];
    }
}