<?php

namespace App\Controllers\Traits;

trait ManagesUserOperations
{
    /**
     * Find user or redirect with error
     */
    protected function findUserOrFail($userModel, int $id, string $redirectTo = 'admin/users'): ?object
    {
        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect($redirectTo);
            return null;
        }

        return $user;
    }

    /**
     * Check if email is unique
     */
    protected function isEmailUnique($userModel, string $email, ?int $excludeId = null): bool
    {
        $existingUser = $userModel->findByEmail($email);

        if (!$existingUser) {
            return true;
        }

        return $excludeId && $existingUser->id == $excludeId;
    }

    /**
     * Check if user has active bookings
     */
    protected function userHasActiveBookings($bookingModel, int $userId): bool
    {
        $result = $bookingModel->raw(
            "SELECT COUNT(*) as count FROM bookings 
             WHERE user_id = :user_id 
             AND status IN ('pending', 'confirmed', 'checked_in')",
            [':user_id' => $userId]
        );

        return ($result[0]->count ?? 0) > 0;
    }

    /**
     * Get user statistics
     */
    protected function getUserStatistics($bookingModel, int $userId): ?object
    {
        $result = $bookingModel->raw(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as total_spent,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
             FROM bookings
             WHERE user_id = :user_id",
            [':user_id' => $userId]
        );

        return $result[0] ?? null;
    }

    /**
     * Get users list statistics
     */
    protected function getUsersListStats($userModel): array
    {
        return [
            'total' => $userModel->count(),
            'guests' => count($userModel->getGuests()),
            'admins' => count($userModel->getAdmins())
        ];
    }

    /**
     * Prevent self modification
     */
    protected function preventSelfModification(int $userId, string $action, string $redirectTo): bool
    {
        if ($userId == $_SESSION['user_id']) {
            $this->setFlash('error', "Tidak dapat {$action} akun sendiri");
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Update session if editing own profile
     */
    protected function updateOwnSession(int $userId, array $data): void
    {
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['user']->name = $data['name'];
            $_SESSION['user']->email = $data['email'];
            $_SESSION['user']->phone = $data['phone'];
        }
    }
}