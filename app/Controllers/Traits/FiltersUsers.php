<?php

namespace App\Controllers\Traits;

trait FiltersUsers
{
    /**
     * Get filtered users based on request
     */
    protected function getFilteredUsers($userModel, ?string $role, ?string $search): array
    {
        if ($role && $this->isValidRole($role)) {
            return $userModel->getByRole($role);
        }

        if ($search) {
            return $this->searchUsers($userModel, $search);
        }

        return $userModel->all();
    }

    /**
     * Search users by name, email, or phone
     */
    protected function searchUsers($userModel, string $search): array
    {
        return $userModel->raw(
            "SELECT * FROM users 
             WHERE name LIKE :search 
             OR email LIKE :search 
             OR phone LIKE :search
             ORDER BY created_at DESC",
            [':search' => '%' . $search . '%']
        );
    }

    /**
     * Get user filter parameters
     */
    protected function getUserFilterParams(): array
    {
        return [
            'role' => $_GET['role'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
    }
}