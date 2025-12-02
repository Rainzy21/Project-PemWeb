<?php

namespace App\Controllers\Traits;

trait RequiresAdmin
{
    /**
     * Check if user is admin, redirect if not
     */
    protected function requireAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('/');
            exit;
        }
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']->role === 'admin';
    }
}