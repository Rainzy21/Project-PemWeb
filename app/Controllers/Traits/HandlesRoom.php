<?php

namespace App\Controllers\Traits;

trait HandlesRoom
{
    /**
     * Get room or redirect with error
     */
    protected function findRoomOrFail($roomModel, int $id, string $redirectTo = 'rooms'): ?object
    {
        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect($redirectTo);
            return null;
        }

        return $room;
    }

    /**
     * Get room or return JSON error
     */
    protected function findRoomOrJsonFail($roomModel, int $id): ?object
    {
        $room = $roomModel->find($id);

        if (!$room) {
            $this->json(['error' => 'Kamar tidak ditemukan'], 404);
            return null;
        }

        return $room;
    }

    /**
     * Check room availability or redirect
     */
    protected function ensureRoomAvailable(object $room, string $redirectTo = 'rooms'): bool
    {
        if (!$room->is_available) {
            $this->setFlash('error', 'Kamar tidak tersedia');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Check room available for dates or redirect
     */
    protected function ensureRoomAvailableForDates($roomModel, int $roomId, string $checkIn, string $checkOut, string $redirectTo): bool
    {
        if (!$roomModel->isAvailableForDates($roomId, $checkIn, $checkOut)) {
            $this->setFlash('error', 'Kamar tidak tersedia untuk tanggal tersebut');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }
}