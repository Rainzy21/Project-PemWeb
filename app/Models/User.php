<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasPassword;
use App\Models\Traits\HasImage;
use App\Models\Traits\HasRole;
use App\Models\Traits\Searchable;

class User extends Model
{
    use HasPassword, HasImage, HasRole, Searchable;

    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password_hash', 'phone', 'profile_image', 'role'];

    // Trait configurations
    protected string $imageColumn = 'profile_image';
    protected string $uploadDir = 'uploads/profiles';
    protected array $searchable = ['name', 'email', 'phone'];

    /**
     * Register new user
     */
    public function register(array $data): int|false
    {
        if (isset($data['password'])) {
            $data['password_hash'] = $this->hashPassword($data['password']);
            unset($data['password']);
        }
        
        $data['role'] = $data['role'] ?? 'guest';
        
        return $this->create($data);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?object
    {
        return $this->findBy('email', $email);
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Update profile (excluding password)
     */
    public function updateProfile(int $id, array $data): bool
    {
        unset($data['password'], $data['password_hash']);
        return $this->update($id, $data);
    }

    /**
     * Update profile image
     */
    public function updateProfileImage(int $id, string $imagePath): bool
    {
        return $this->updateImage($id, $imagePath);
    }
}