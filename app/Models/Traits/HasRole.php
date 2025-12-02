<?php

namespace App\Models\Traits;

trait HasRole
{
    /**
     * Get users by role
     */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role);
    }

    /**
     * Get all admins
     */
    public function getAdmins(): array
    {
        return $this->getByRole('admin');
    }

    /**
     * Get all guests
     */
    public function getGuests(): array
    {
        return $this->getByRole('guest');
    }

    /**
     * Check if user has role
     */
    public function hasRole(object $user, string $role): bool
    {
        return ($user->role ?? '') === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(object $user): bool
    {
        return $this->hasRole($user, 'admin');
    }

    /**
     * Check if user is guest
     */
    public function isGuest(object $user): bool
    {
        return $this->hasRole($user, 'guest');
    }

    /**
     * Update user role
     */
    public function updateRole(int $id, string $role): bool
    {
        return $this->update($id, ['role' => $role]);
    }

    /**
     * Toggle role between admin and guest
     */
    public function toggleRole(int $id): bool
    {
        $user = $this->find($id);
        
        if (!$user) {
            return false;
        }
        
        $newRole = $user->role === 'admin' ? 'guest' : 'admin';
        return $this->updateRole($id, $newRole);
    }
}