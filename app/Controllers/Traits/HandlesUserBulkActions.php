<?php

namespace App\Controllers\Traits;

trait HandlesUserBulkActions
{
    /**
     * Get bulk action input for users
     */
    protected function getUserBulkInput(): array
    {
        $userIds = $_POST['user_ids'] ?? [];
        
        // Remove current user from selection
        $userIds = array_filter($userIds, fn($id) => $id != $_SESSION['user_id']);

        return [
            'action' => $_POST['action'] ?? '',
            'ids' => $userIds
        ];
    }

    /**
     * Validate user bulk input
     */
    protected function validateUserBulkInput(array $ids, string $redirectTo): bool
    {
        if (empty($ids)) {
            $this->setFlash('error', 'Pilih minimal satu user atau tidak dapat melakukan aksi pada akun sendiri');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Process bulk role change
     */
    protected function processBulkRoleChange($userModel, array $userIds, string $role): int
    {
        $successCount = 0;

        foreach ($userIds as $id) {
            if ($userModel->update($id, ['role' => $role])) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Process bulk user delete
     */
    protected function processBulkUserDelete($userModel, $bookingModel, array $userIds): int
    {
        $successCount = 0;

        foreach ($userIds as $id) {
            if ($this->userHasActiveBookings($bookingModel, $id)) {
                continue;
            }

            $user = $userModel->find($id);
            if ($user) {
                $this->deleteImage($user->profile_image);
                if ($userModel->delete($id)) {
                    $successCount++;
                }
            }
        }

        return $successCount;
    }
}