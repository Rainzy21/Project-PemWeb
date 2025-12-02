<?php

namespace Core\Traits;

trait ChecksAuth
{
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Require login - redirect if not authenticated
     */
    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Please login first');
            $this->redirect('login');
        }
    }

    /**
     * Get current user ID
     */
    protected function userId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user
     */
    protected function user(): ?object
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin(): bool
    {
        return $this->isLoggedIn() && ($_SESSION['user']->role ?? '') === 'admin';
    }

    /**
     * Require guest (not logged in)
     */
    protected function requireGuest(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }
    }
}