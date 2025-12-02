<?php

namespace App\Models\Traits;

trait HasImage
{
    /**
     * Get image column name
     */
    protected function getImageColumn(): string
    {
        return $this->imageColumn ?? 'image';
    }

    /**
     * Get upload directory
     */
    protected function getUploadDir(): string
    {
        return $this->uploadDir ?? 'uploads';
    }

    /**
     * Update image
     */
    public function updateImage(int $id, string $imagePath): bool
    {
        return $this->update($id, [
            $this->getImageColumn() => $imagePath
        ]);
    }

    /**
     * Delete old image file
     */
    public function deleteOldImage(string $imagePath): bool
    {
        $fullPath = dirname(__DIR__, 3) . '/public/' . $imagePath;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }

    /**
     * Get image URL
     */
    public function getImageUrl(?string $imagePath, string $default = '/assets/images/placeholder.jpg'): string
    {
        if (empty($imagePath)) {
            return BASE_URL . $default;
        }
        return BASE_URL . '/storage/' . $imagePath;
    }
}