<?php

namespace App\Models\Traits;

trait HasPassword
{
    /**
     * Hash password
     */
    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Update password
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password_hash' => $this->hashPassword($newPassword)
        ]);
    }

    /**
     * Check if password needs rehash
     */
    public function needsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, PASSWORD_DEFAULT);
    }
}