<?php

namespace App\Controllers\Traits;

trait HandlesAdminBooking
{
    /**
     * Find booking or redirect
     */
    protected function findBookingOrFail($bookingModel, int $id, string $redirectTo = 'admin/bookings'): ?object
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
     * Find booking with details or redirect
     */
    protected function findBookingWithDetailsOrFail($bookingModel, int $id, string $redirectTo = 'admin/bookings'): ?object
    {
        $booking = $bookingModel->getWithDetails($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect($redirectTo);
            return null;
        }

        return $booking;
    }

    /**
     * Process check-in with room update
     */
    protected function processCheckIn($bookingModel, $roomModel, object $booking, int $id): bool
    {
        if ($bookingModel->checkIn($id)) {
            $roomModel->setAvailability($booking->room_id, false);
            return true;
        }

        return false;
    }

    /**
     * Process check-out with room update
     */
    protected function processCheckOut($bookingModel, $roomModel, object $booking, int $id): bool
    {
        if ($bookingModel->checkOut($id)) {
            $roomModel->setAvailability($booking->room_id, true);
            return true;
        }

        return false;
    }

    /**
     * Get admin booking input data
     */
    protected function getAdminBookingInput(): array
    {
        return [
            'user_id' => $_POST['user_id'] ?? '',
            'room_id' => $_POST['room_id'] ?? '',
            'check_in' => $_POST['check_in_date'] ?? '',
            'check_out' => $_POST['check_out_date'] ?? '',
            'status' => $_POST['status'] ?? 'confirmed'
        ];
    }

    /**
     * Validate admin booking input
     */
    protected function validateAdminBookingInput(array $input, string $redirectTo): bool
    {
        if (empty($input['user_id']) || empty($input['room_id']) || 
            empty($input['check_in']) || empty($input['check_out'])) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect($redirectTo);
            return false;
        }

        if ($input['check_out'] <= $input['check_in']) {
            $this->setFlash('error', 'Tanggal check-out harus lebih besar dari check-in');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }
}