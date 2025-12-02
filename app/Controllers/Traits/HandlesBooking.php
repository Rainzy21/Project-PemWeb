<?php

namespace App\Controllers\Traits;

trait HandlesBooking
{
    /**
     * Validate booking ownership
     */
    protected function validateBookingOwnership(object $booking, bool $allowAdmin = true): bool
    {
        $isOwner = $booking->user_id == $_SESSION['user_id'];
        $isAdmin = $allowAdmin && ($_SESSION['user']->role ?? '') === 'admin';

        return $isOwner || $isAdmin;
    }

    /**
     * Check if booking can be cancelled
     */
    protected function isCancellable(object $booking): bool
    {
        return in_array($booking->status, ['pending', 'confirmed']);
    }

    /**
     * Get booking or redirect with error
     */
    protected function findBookingOrFail($bookingModel, int $id, string $redirectTo = 'my-bookings'): ?object
    {
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect($redirectTo);
            return null;
        }

        return $booking;
    }

    /**
     * Get booking with details or redirect
     */
    protected function findBookingWithDetailsOrFail($bookingModel, int $id, string $redirectTo = 'my-bookings'): ?object
    {
        $booking = $bookingModel->getWithDetails($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect($redirectTo);
            return null;
        }

        return $booking;
    }
}