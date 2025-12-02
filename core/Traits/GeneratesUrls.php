<?php

namespace Core\Traits;

trait GeneratesUrls
{
    /**
     * Generate URL
     */
    public function url(string $path = ''): string
    {
        return BASE_URL . ltrim($path, '/');
    }

    /**
     * Asset URL (css, js, images)
     */
    public function asset(string $path): string
    {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }

    /**
     * Storage URL (uploads)
     */
    public function storage(string $path): string
    {
        return BASE_URL . 'storage/' . ltrim($path, '/');
    }

    /**
     * Check if current URL matches
     */
    public function isActive(string $path): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return strpos($currentPath, $path) !== false;
    }

    /**
     * Active class helper
     */
    public function activeClass(string $path, string $class = 'active'): string
    {
        return $this->isActive($path) ? $class : '';
    }
}