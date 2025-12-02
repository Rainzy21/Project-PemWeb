<?php

namespace App\Controllers\Traits;

trait HandlesBulkOperations
{
    /**
     * Get bulk action input
     */
    protected function getBulkActionInput(): array
    {
        return [
            'action' => $_POST['action'] ?? '',
            'ids' => $_POST['room_ids'] ?? []
        ];
    }

    /**
     * Validate bulk action input
     */
    protected function validateBulkInput(array $ids, string $redirectTo): bool
    {
        if (empty($ids)) {
            $this->setFlash('error', 'Pilih minimal satu item');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Process bulk availability update
     */
    protected function processBulkAvailability($roomModel, array $roomIds, bool $available): int
    {
        $successCount = 0;

        foreach ($roomIds as $id) {
            if ($roomModel->setAvailability($id, $available)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Process bulk delete
     */
    protected function processBulkDelete($roomModel, $bookingModel, array $roomIds): int
    {
        $successCount = 0;

        foreach ($roomIds as $id) {
            if ($this->hasActiveBookings($bookingModel, $id)) {
                continue;
            }

            $room = $roomModel->find($id);
            if ($room) {
                $this->deleteImage($room->image);
                if ($roomModel->delete($id)) {
                    $successCount++;
                }
            }
        }

        return $successCount;
    }
}