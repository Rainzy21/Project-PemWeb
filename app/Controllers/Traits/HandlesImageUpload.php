<?php

namespace App\Controllers\Traits;

trait HandlesImageUpload
{
    protected array $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp'];
    protected int $maxImageSize = 2 * 1024 * 1024; // 2MB

    /**
     * Upload image file
     */
    protected function uploadImage(array $file, string $subDir = 'rooms'): ?string
    {
        if (!$this->validateImageFile($file)) {
            return null;
        }

        $uploadDir = $this->getUploadPath($subDir);
        $this->ensureDirectoryExists($uploadDir);

        $filename = $this->generateImageFilename($file['name'], $subDir);
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return "storage/uploads/{$subDir}/{$filename}";
        }

        $this->setFlash('error', 'Gagal mengupload gambar');
        return null;
    }

    /**
     * Validate image file
     */
    protected function validateImageFile(array $file): bool
    {
        if (!in_array($file['type'], $this->allowedImageTypes)) {
            $this->setFlash('error', 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WebP');
            return false;
        }

        if ($file['size'] > $this->maxImageSize) {
            $this->setFlash('error', 'Ukuran file maksimal 2MB');
            return false;
        }

        return true;
    }

    /**
     * Delete image file
     */
    protected function deleteImage(?string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }

        $fullPath = dirname(__DIR__, 2) . '/' . $imagePath;

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Get upload path
     */
    protected function getUploadPath(string $subDir): string
    {
        return dirname(__DIR__, 2) . "/storage/uploads/{$subDir}/";
    }

    /**
     * Generate unique filename
     */
    protected function generateImageFilename(string $originalName, string $prefix): string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        return "{$prefix}_" . uniqid() . '_' . time() . '.' . $extension;
    }

    /**
     * Ensure directory exists
     */
    protected function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Check if file was uploaded
     */
    protected function hasUploadedFile(string $fieldName): bool
    {
        return isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK;
    }
}