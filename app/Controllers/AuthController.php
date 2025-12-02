<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesAuth;
use App\Controllers\Traits\HandlesOldInput;
use App\Controllers\Traits\ValidatesRequest;

class AuthController extends Controller
{
    use HandlesAuth, HandlesOldInput, ValidatesRequest;

    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        if ($this->redirectIfAuthenticated()) return;

        $this->view->setLayout('main')->render('auth/login', [
            'title' => 'Login - ' . APP_NAME
        ]);
    }

    /**
     * Proses login
     */
    public function doLogin()
    {
        if (!$this->isPost()) {
            return $this->redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Email dan password harus diisi');
            return $this->redirect('login');
        }

        $userModel = $this->loadModel('User');

        if (!$user = $this->attemptLogin($userModel, $email, $password)) {
            return $this->redirect('login');
        }

        $this->setUserSession($user);
        $this->setFlash('success', "Selamat datang, {$user->name}!");
        $this->redirectByRole($user);
    }

    /**
     * Attempt login
     */
    protected function attemptLogin($userModel, string $email, string $password): ?object
    {
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->setFlash('error', 'Email tidak terdaftar');
            return null;
        }

        if (!$userModel->verifyPassword($password, $user->password_hash)) {
            $this->setFlash('error', 'Password salah');
            return null;
        }

        return $user;
    }

    /**
     * Tampilkan halaman register
     */
    public function register()
    {
        if ($this->redirectIfAuthenticated()) return;

        $this->view->setLayout('main')->render('auth/register', [
            'title' => 'Register - ' . APP_NAME
        ]);
    }

    /**
     * Proses register
     */
    public function doRegister()
    {
        if (!$this->isPost()) {
            return $this->redirect('register');
        }

        $data = $this->getRegistrationData();
        $this->setOldInput($data);

        if (!$this->validateRegistration($data)) {
            return $this->redirect('register');
        }

        $userModel = $this->loadModel('User');

        if ($userModel->emailExists($data['email'])) {
            $this->setFlash('error', 'Email sudah terdaftar');
            return $this->redirect('register');
        }

        if ($userModel->register($data)) {
            $this->clearOldInput();
            $this->setFlash('success', 'Registrasi berhasil! Silakan login');
            return $this->redirect('login');
        }

        $this->setFlash('error', 'Registrasi gagal. Silakan coba lagi');
        $this->redirect('register');
    }

    /**
     * Get registration data from POST
     */
    protected function getRegistrationData(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone' => trim($_POST['phone'] ?? '')
        ];
    }

    /**
     * Validate registration data
     */
    protected function validateRegistration(array $data): bool
    {
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            return false;
        }

        if (!$this->isValidEmail($data['email'])) {
            $this->setFlash('error', 'Format email tidak valid');
            return false;
        }

        if (!$this->isPasswordConfirmed($data['password'], $_POST['confirm_password'] ?? '')) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            return false;
        }

        return true;
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->destroySession();
        $this->setFlash('success', 'Anda telah logout');
        $this->redirect('login');
    }

    /**
     * Tampilkan profile
     */
    public function profile()
    {
        $this->requireLogin();

        $user = $this->loadModel('User')->find($_SESSION['user_id']);

        $this->view->setLayout('main')->render('auth/profile', [
            'title' => 'Profile - ' . APP_NAME,
            'user' => $user
        ]);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('profile');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? '')
        ];

        if (!$this->validateOrFail($data, ['name' => 'required|min:3', 'phone' => 'required'], 'profile')) {
            return;
        }

        if ($this->loadModel('User')->update($_SESSION['user_id'], $data)) {
            $this->updateUserSession($data);
            $this->setFlash('success', 'Profile berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal update profile');
        }

        $this->redirect('profile');
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('profile');
        }

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$this->validatePasswordChange($current, $new, $confirm)) {
            return $this->redirect('profile');
        }

        $userModel = $this->loadModel('User');
        $user = $userModel->find($_SESSION['user_id']);

        if (!$userModel->verifyPassword($current, $user->password_hash)) {
            $this->setFlash('error', 'Password saat ini salah');
            return $this->redirect('profile');
        }

        if ($userModel->updatePassword($_SESSION['user_id'], $new)) {
            $this->setFlash('success', 'Password berhasil diubah');
        } else {
            $this->setFlash('error', 'Gagal mengubah password');
        }

        $this->redirect('profile');
    }

    /**
     * Validate password change
     */
    protected function validatePasswordChange(string $current, string $new, string $confirm): bool
    {
        if (empty($current) || empty($new) || empty($confirm)) {
            $this->setFlash('error', 'Semua field harus diisi');
            return false;
        }

        if (!$this->hasMinLength($new, 6)) {
            $this->setFlash('error', 'Password baru minimal 6 karakter');
            return false;
        }

        if (!$this->isPasswordConfirmed($new, $confirm)) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            return false;
        }

        return true;
    }

    /**
     * Forgot password page
     */
    public function forgotPassword()
    {
        $this->view->setLayout('main')->render('auth/forgot-password', [
            'title' => 'Lupa Password - ' . APP_NAME
        ]);
    }

    /**
     * Process forgot password
     */
    public function doForgotPassword()
    {
        if (!$this->isPost()) {
            return $this->redirect('forgot-password');
        }

        // Always show success (security)
        $this->setFlash('success', 'Jika email terdaftar, link reset password telah dikirim');
        $this->redirect('login');
    }
}