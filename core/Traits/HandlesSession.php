<?php

namespace Core\Traits;

trait HandlesSession
{
    /**
     * Get flash message
     */
    public function flash(?string $type = null): mixed
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        if ($type) {
            return $flash['type'] === $type ? $flash['message'] : null;
        }

        return $flash;
    }

    /**
     * Check if user is logged in
     */
    public function auth(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user
     */
    public function user(): ?object
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->auth() && ($_SESSION['user']->role ?? '') === 'admin';
    }

    /**
     * Old input value (for form)
     */
    public function old(string $key, string $default = ''): string
    {
        return $_SESSION['old'][$key] ?? $default;
    }

    /**
     * Check if has old input
     */
    public function hasOld(string $key): bool
    {
        return isset($_SESSION['old'][$key]);
    }

    /**
     * Get CSRF token
     */
    public function csrf(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Generate CSRF input field
     */
    public function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->csrf() . '">';
    }
}