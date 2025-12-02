<?php

namespace App\Controllers\Traits;

trait ValidatesBookingDates
{
    /**
     * Validate search dates
     */
    protected function validateSearchDates(string $checkIn, string $checkOut, string $redirectTo): bool
    {
        if (empty($checkIn) || empty($checkOut)) {
            $this->setFlash('error', 'Tanggal check-in dan check-out harus diisi');
            $this->redirect($redirectTo);
            return false;
        }

        return $this->validateBookingDates($checkIn, $checkOut, $redirectTo);
    }

    /**
     * Validate booking dates
     */
    protected function validateBookingDates(string $checkIn, string $checkOut, string $redirectTo): bool
    {
        $today = date('Y-m-d');

        if ($checkIn < $today) {
            $this->setFlash('error', 'Tanggal check-in tidak boleh kurang dari hari ini');
            $this->redirect($redirectTo);
            return false;
        }

        if ($checkOut <= $checkIn) {
            $this->setFlash('error', 'Tanggal check-out harus lebih besar dari check-in');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Validate dates for JSON response
     */
    protected function validateDatesOrJsonFail(string $checkIn, string $checkOut): bool
    {
        if (empty($checkIn) || empty($checkOut)) {
            $this->json(['error' => 'Tanggal harus diisi'], 400);
            return false;
        }

        return true;
    }

    /**
     * Get booking input data from POST
     */
    protected function getBookingInput(): array
    {
        return [
            'room_id' => $_POST['room_id'] ?? '',
            'check_in' => $_POST['check_in_date'] ?? '',
            'check_out' => $_POST['check_out_date'] ?? ''
        ];
    }

    /**
     * Validate booking input is not empty
     */
    protected function validateBookingInput(array $input, string $redirectTo): bool
    {
        if (empty($input['room_id']) || empty($input['check_in']) || empty($input['check_out'])) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }
}