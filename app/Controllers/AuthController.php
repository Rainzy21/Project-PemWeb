<?php

namespace App\Controllers;

use Core\Controller;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        // Jika sudah login, redirect ke home
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

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
            $this->redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input
        $errors = $this->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', 'Email dan password harus diisi');
            $this->redirect('login');
        }

        // Cari user
        $userModel = $this->loadModel('User');
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->setFlash('error', 'Email tidak terdaftar');
            $this->redirect('login');
        }

        // Verifikasi password
        if (!$userModel->verifyPassword($password, $user->password_hash)) {
            $this->setFlash('error', 'Password salah');
            $this->redirect('login');
        }

        // Set session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user'] = $user;

        $this->setFlash('success', 'Selamat datang, ' . $user->name . '!');

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            $this->redirect('admin');
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Tampilkan halaman register
     */
    public function register()
    {
        // Jika sudah login, redirect ke home
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

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
            $this->redirect('register');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone' => trim($_POST['phone'] ?? '')
        ];
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Simpan old input
        $_SESSION['old'] = $data;

        // Validasi input
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('register');
        }

        // Validasi email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Format email tidak valid');
            $this->redirect('register');
        }

        // Validasi confirm password
        if ($data['password'] !== $confirmPassword) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            $this->redirect('register');
        }

        $userModel = $this->loadModel('User');

        // Cek email sudah terdaftar
        if ($userModel->emailExists($data['email'])) {
            $this->setFlash('error', 'Email sudah terdaftar');
            $this->redirect('register');
        }

        // Register user
        $userId = $userModel->register($data);

        if ($userId) {
            unset($_SESSION['old']); // Hapus old input
            $this->setFlash('success', 'Registrasi berhasil! Silakan login');
            $this->redirect('login');
        } else {
            $this->setFlash('error', 'Registrasi gagal. Silakan coba lagi');
            $this->redirect('register');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Hapus semua session
        $_SESSION = [];

        // Hapus session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy session
        session_destroy();

        // Start new session untuk flash message
        session_start();
        $this->setFlash('success', 'Anda telah logout');
        $this->redirect('login');
    }

    /**
     * Tampilkan halaman forgot password
     */
    public function forgotPassword()
    {
        $this->view->setLayout('main')->render('auth/forgot-password', [
            'title' => 'Lupa Password - ' . APP_NAME
        ]);
    }

    /**
     * Proses forgot password
     */
    public function doForgotPassword()
    {
        if (!$this->isPost()) {
            $this->redirect('forgot-password');
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $this->setFlash('error', 'Email harus diisi');
            $this->redirect('forgot-password');
        }

        $userModel = $this->loadModel('User');
        $user = $userModel->findByEmail($email);

        // Selalu tampilkan pesan sukses (keamanan)
        $this->setFlash('success', 'Jika email terdaftar, link reset password telah dikirim');
        $this->redirect('login');
    }

    /**
     * Tampilkan profile
     */
    public function profile()
    {
        $this->requireLogin();

        $userModel = $this->loadModel('User');
        $user = $userModel->find($_SESSION['user_id']);

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
            $this->redirect('profile');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? '')
        ];

        // Validasi
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'phone' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('profile');
        }

        $userModel = $this->loadModel('User');

        if ($userModel->update($_SESSION['user_id'], $data)) {
            // Update session
            $_SESSION['user']->name = $data['name'];
            $_SESSION['user']->phone = $data['phone'];

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
            $this->redirect('profile');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validasi
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect('profile');
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('error', 'Password baru minimal 6 karakter');
            $this->redirect('profile');
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            $this->redirect('profile');
        }

        $userModel = $this->loadModel('User');
        $user = $userModel->find($_SESSION['user_id']);

        // Verifikasi password lama
        if (!$userModel->verifyPassword($currentPassword, $user->password_hash)) {
            $this->setFlash('error', 'Password saat ini salah');
            $this->redirect('profile');
        }

        // Update password
        if ($userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
            $this->setFlash('success', 'Password berhasil diubah');
        } else {
            $this->setFlash('error', 'Gagal mengubah password');
        }

        $this->redirect('profile');
    }
}