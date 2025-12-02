<?php

namespace App\Controllers\Traits;

trait HandlesOldInput
{
    /**
     * Simpan old input ke session
     */
    protected function setOldInput(array $data): void
    {
        $_SESSION['old'] = $data;
    }

    /**
     * Hapus old input dari session
     */
    protected function clearOldInput(): void
    {
        unset($_SESSION['old']);
    }

    /**
     * Get old input value
     */
    protected function getOldInput(string $key, $default = ''): mixed
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}