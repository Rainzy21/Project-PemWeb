<?php

namespace App\Controllers\Traits;

trait ManagesBookingStatus
{
    protected array $validStatuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

    /**
     * Validate booking status
     */
    protected function isValidStatus(string $status): bool
    {
        return in_array($status, $this->validStatuses);
    }

    /**
     * Check if booking can be confirmed
     */
    protected function canConfirm(object $booking): bool
    {
        return $booking->status === 'pending';
    }

    /**
     * Check if booking can check-in
     */
    protected function canCheckIn(object $booking): bool
    {
        if ($booking->status !== 'confirmed') {
            return false;
        }

        return $booking->check_in_date <= date('Y-m-d');
    }

    /**
     * Check if booking can check-out
     */
    protected function canCheckOut(object $booking): bool
    {
        return $booking->status === 'checked_in';
    }

    /**
     * Check if booking can be cancelled
     */
    protected function canCancel(object $booking): bool
    {
        return in_array($booking->status, ['pending', 'confirmed']);
    }

    /**
     * Check if booking can be deleted
     */
    protected function canDelete(object $booking): bool
    {
        return in_array($booking->status, ['cancelled', 'checked_out']);
    }

    /**
     * Get status validation error message
     */
    protected function getStatusErrorMessage(string $action, object $booking): string
    {
        return match ($action) {
            'confirm' => 'Hanya booking pending yang bisa dikonfirmasi',
            'check_in' => $booking->status !== 'confirmed' 
                ? 'Hanya booking confirmed yang bisa check-in'
                : 'Belum waktunya check-in. Tanggal check-in: ' . $booking->check_in_date,
            'check_out' => 'Hanya guest yang sudah check-in yang bisa check-out',
            'cancel' => 'Booking ini tidak dapat dibatalkan',
            'delete' => 'Hanya booking yang cancelled atau checked_out yang bisa dihapus',
            default => 'Aksi tidak valid'
        };
    }
}