<?php

namespace App\Controllers\Traits;

trait HandlesAuth
{
    /**
     * Set user session setelah login
     */
    protected function setUserSession(object $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user'] = $user;
    }

    /**
     * Update session user data
     */
    protected function updateUserSession(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION['user']->{$key} = $value;
        }
    }

    /**
     * Destroy session completely
     */
    protected function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        session_start();
    }

    /**
     * Redirect jika sudah login
     */
    protected function redirectIfAuthenticated(): bool
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return true;
        }
        return false;
    }

    /**
     * Redirect berdasarkan role
     */
    protected function redirectByRole(object $user): void
    {
        $this->redirect($user->role === 'admin' ? 'admin' : '/');
    }
}