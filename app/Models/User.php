<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password_hash', 'phone', 'profile_image', 'role'];

    /**
     * Register user baru
     */
    public function register(array $data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']); // Hapus password plain
        $data['role'] = $data['role'] ?? 'guest';
        
        return $this->create($data);
    }

    /**
     * Login - cari user berdasarkan email
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Cek email sudah ada atau belum
     */
    public function emailExists($email)
    {
        return $this->findByEmail($email) !== false;
    }

    /**
     * Update password
     */
    public function updatePassword($id, $newPassword)
    {
        return $this->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        return $this->where('role', $role);
    }

    /**
     * Get all admins
     */
    public function getAdmins()
    {
        return $this->getByRole('admin');
    }

    /**
     * Get all guests
     */
    public function getGuests()
    {
        return $this->getByRole('guest');
    }

    /**
     * Update profile image
     */
    public function updateProfileImage($id, $imagePath)
    {
        return $this->update($id, ['profile_image' => $imagePath]);
    }
}