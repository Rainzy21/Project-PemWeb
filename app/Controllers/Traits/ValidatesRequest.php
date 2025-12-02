<?php

namespace App\Controllers\Traits;

trait ValidatesRequest
{
    /**
     * Validate email format
     */
    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate password confirmation
     */
    protected function isPasswordConfirmed(string $password, string $confirm): bool
    {
        return $password === $confirm;
    }

    /**
     * Validate minimum length
     */
    protected function hasMinLength(string $value, int $min): bool
    {
        return strlen($value) >= $min;
    }

    /**
     * Validate & redirect with error
     */
    protected function validateOrFail(array $data, array $rules, string $redirectTo): bool
    {
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }
}