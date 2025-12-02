<?php

namespace App\Controllers\Traits;

trait ValidatesUserData
{
    protected array $validRoles = ['admin', 'guest'];

    protected array $userCreateRules = [
        'name' => 'required|min:3',
        'email' => 'required',
        'password' => 'required|min:6',
        'phone' => 'required'
    ];

    protected array $userUpdateRules = [
        'name' => 'required|min:3',
        'email' => 'required',
        'phone' => 'required'
    ];

    /**
     * Get user data from POST
     */
    protected function getUserInputData(bool $includePassword = true): array
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'guest'
        ];

        if ($includePassword) {
            $data['password'] = $_POST['password'] ?? '';
        }

        return $data;
    }

    /**
     * Validate user data for create
     */
    protected function validateUserCreate(array $data, string $redirectTo): bool
    {
        $errors = $this->validate($data, $this->userCreateRules);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $_SESSION['old'] = $data;
            $this->redirect($redirectTo);
            return false;
        }

        return $this->validateUserCommon($data, $redirectTo);
    }

    /**
     * Validate user data for update
     */
    protected function validateUserUpdate(array $data, string $redirectTo): bool
    {
        $errors = $this->validate($data, $this->userUpdateRules);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect($redirectTo);
            return false;
        }

        return $this->validateUserCommon($data, $redirectTo);
    }

    /**
     * Common validation rules
     */
    protected function validateUserCommon(array $data, string $redirectTo): bool
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Format email tidak valid');
            $this->redirect($redirectTo);
            return false;
        }

        if (!$this->isValidRole($data['role'])) {
            $this->setFlash('error', 'Role tidak valid');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Check if role is valid
     */
    protected function isValidRole(string $role): bool
    {
        return in_array($role, $this->validRoles);
    }

    /**
     * Validate password reset input
     */
    protected function validatePasswordReset(string $password, string $confirm, string $redirectTo): bool
    {
        if (empty($password) || strlen($password) < 6) {
            $this->setFlash('error', 'Password minimal 6 karakter');
            $this->redirect($redirectTo);
            return false;
        }

        if ($password !== $confirm) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }
}