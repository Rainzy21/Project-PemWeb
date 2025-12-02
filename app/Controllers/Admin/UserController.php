<?php

namespace App\Controllers\Admin;

use Core\Controller;

class UserController extends Controller
{
    /**
     * Constructor - require admin login
     */
    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->requireAdmin();
    }

    /**
     * Check if user is admin
     */
    private function requireAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('/');
        }
    }

    /**
     * List semua user
     */
    public function index()
    {
        $userModel = $this->loadModel('User');

        // Filter by role
        $role = $_GET['role'] ?? null;
        $search = $_GET['search'] ?? null;

        if ($role && in_array($role, ['admin', 'guest'])) {
            $users = $userModel->getByRole($role);
        } elseif ($search) {
            $users = $userModel->raw(
                "SELECT * FROM users 
                 WHERE name LIKE :search 
                 OR email LIKE :search 
                 OR phone LIKE :search
                 ORDER BY created_at DESC",
                [':search' => '%' . $search . '%']
            );
        } else {
            $users = $userModel->all();
        }

        // Statistics
        $stats = [
            'total' => $userModel->count(),
            'guests' => count($userModel->getGuests()),
            'admins' => count($userModel->getAdmins())
        ];

        $this->view->setLayout('admin')->render('admin/users/index', [
            'title' => 'Kelola Users - ' . APP_NAME,
            'users' => $users,
            'stats' => $stats,
            'selectedRole' => $role,
            'searchQuery' => $search
        ]);
    }

    /**
     * Detail user
     */
    public function detail($id)
    {
        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');

        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        // Get user's bookings
        $bookings = $bookingModel->getByUser($id);

        // User statistics
        $userStats = $bookingModel->raw(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as total_spent,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
             FROM bookings
             WHERE user_id = :user_id",
            [':user_id' => $id]
        );

        $this->view->setLayout('admin')->render('admin/users/detail', [
            'title' => 'Detail User - ' . APP_NAME,
            'user' => $user,
            'bookings' => $bookings,
            'userStats' => $userStats[0] ?? null
        ]);
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $this->view->setLayout('admin')->render('admin/users/form', [
            'title' => 'Tambah User - ' . APP_NAME,
            'user' => null,
            'action' => 'create'
        ]);
    }

    /**
     * Simpan user baru
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'guest'
        ];

        // Simpan old input
        $_SESSION['old'] = $data;

        // Validasi
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/users/create');
        }

        // Validasi email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Format email tidak valid');
            $this->redirect('admin/users/create');
        }

        // Validasi role
        if (!in_array($data['role'], ['admin', 'guest'])) {
            $this->setFlash('error', 'Role tidak valid');
            $this->redirect('admin/users/create');
        }

        $userModel = $this->loadModel('User');

        // Cek email sudah terdaftar
        if ($userModel->emailExists($data['email'])) {
            $this->setFlash('error', 'Email sudah terdaftar');
            $this->redirect('admin/users/create');
        }

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['profile_image']);
            if ($imagePath) {
                $data['profile_image'] = $imagePath;
            }
        }

        // Register user
        $userId = $userModel->register($data);

        if ($userId) {
            unset($_SESSION['old']);
            $this->setFlash('success', 'User berhasil ditambahkan');
            $this->redirect('admin/users');
        } else {
            $this->setFlash('error', 'Gagal menambahkan user');
            $this->redirect('admin/users/create');
        }
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $userModel = $this->loadModel('User');
        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        $this->view->setLayout('admin')->render('admin/users/form', [
            'title' => 'Edit User - ' . APP_NAME,
            'user' => $user,
            'action' => 'edit'
        ]);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
        }

        $userModel = $this->loadModel('User');
        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'guest'
        ];

        // Validasi
        $errors = $this->validate($data, [
            'name' => 'required|min:3',
            'email' => 'required',
            'phone' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/users/' . $id . '/edit');
        }

        // Validasi email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Format email tidak valid');
            $this->redirect('admin/users/' . $id . '/edit');
        }

        // Cek email sudah digunakan user lain
        $existingUser = $userModel->findByEmail($data['email']);
        if ($existingUser && $existingUser->id != $id) {
            $this->setFlash('error', 'Email sudah digunakan user lain');
            $this->redirect('admin/users/' . $id . '/edit');
        }

        // Prevent self role change (admin cannot demote themselves)
        if ($id == $_SESSION['user_id'] && $data['role'] !== 'admin') {
            $this->setFlash('error', 'Anda tidak dapat mengubah role sendiri');
            $this->redirect('admin/users/' . $id . '/edit');
        }

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['profile_image']);
            if ($imagePath) {
                // Delete old image
                $this->deleteImage($user->profile_image);
                $data['profile_image'] = $imagePath;
            }
        }

        if ($userModel->update($id, $data)) {
            // Update session if editing own profile
            if ($id == $_SESSION['user_id']) {
                $_SESSION['user']->name = $data['name'];
                $_SESSION['user']->email = $data['email'];
                $_SESSION['user']->phone = $data['phone'];
            }

            $this->setFlash('success', 'User berhasil diupdate');
            $this->redirect('admin/users');
        } else {
            $this->setFlash('error', 'Gagal mengupdate user');
            $this->redirect('admin/users/' . $id . '/edit');
        }
    }

    /**
     * Reset password user
     */
    public function resetPassword($id)
    {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
        }

        $userModel = $this->loadModel('User');
        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validasi
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $this->setFlash('error', 'Password minimal 6 karakter');
            $this->redirect('admin/users/' . $id);
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Konfirmasi password tidak cocok');
            $this->redirect('admin/users/' . $id);
        }

        if ($userModel->updatePassword($id, $newPassword)) {
            $this->setFlash('success', 'Password berhasil direset');
        } else {
            $this->setFlash('error', 'Gagal mereset password');
        }

        $this->redirect('admin/users/' . $id);
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');

        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        // Tidak bisa hapus diri sendiri
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Tidak dapat menghapus akun sendiri');
            $this->redirect('admin/users');
        }

        // Cek apakah ada booking aktif
        $activeBookings = $bookingModel->raw(
            "SELECT COUNT(*) as count FROM bookings 
             WHERE user_id = :user_id 
             AND status IN ('pending', 'confirmed', 'checked_in')",
            [':user_id' => $id]
        );

        if ($activeBookings[0]->count > 0) {
            $this->setFlash('error', 'Tidak dapat menghapus user yang memiliki booking aktif');
            $this->redirect('admin/users');
        }

        // Delete profile image
        $this->deleteImage($user->profile_image);

        if ($userModel->delete($id)) {
            $this->setFlash('success', 'User berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus user');
        }

        $this->redirect('admin/users');
    }

    /**
     * Toggle user role
     */
    public function toggleRole($id)
    {
        $userModel = $this->loadModel('User');
        $user = $userModel->find($id);

        if (!$user) {
            $this->setFlash('error', 'User tidak ditemukan');
            $this->redirect('admin/users');
        }

        // Tidak bisa mengubah role sendiri
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Tidak dapat mengubah role sendiri');
            $this->redirect('admin/users');
        }

        $newRole = $user->role === 'admin' ? 'guest' : 'admin';

        if ($userModel->update($id, ['role' => $newRole])) {
            $this->setFlash('success', "Role user {$user->name} berhasil diubah menjadi {$newRole}");
        } else {
            $this->setFlash('error', 'Gagal mengubah role user');
        }

        $this->redirect('admin/users');
    }

    /**
     * Bulk action
     */
    public function bulkAction()
    {
        if (!$this->isPost()) {
            $this->redirect('admin/users');
        }

        $action = $_POST['action'] ?? '';
        $userIds = $_POST['user_ids'] ?? [];

        if (empty($userIds)) {
            $this->setFlash('error', 'Pilih minimal satu user');
            $this->redirect('admin/users');
        }

        // Remove current user from selection
        $userIds = array_filter($userIds, fn($id) => $id != $_SESSION['user_id']);

        if (empty($userIds)) {
            $this->setFlash('error', 'Tidak dapat melakukan aksi pada akun sendiri');
            $this->redirect('admin/users');
        }

        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');
        $successCount = 0;

        foreach ($userIds as $id) {
            if ($action === 'make_admin') {
                if ($userModel->update($id, ['role' => 'admin'])) {
                    $successCount++;
                }
            } elseif ($action === 'make_guest') {
                if ($userModel->update($id, ['role' => 'guest'])) {
                    $successCount++;
                }
            } elseif ($action === 'delete') {
                // Check for active bookings
                $activeBookings = $bookingModel->raw(
                    "SELECT COUNT(*) as count FROM bookings 
                     WHERE user_id = :user_id 
                     AND status IN ('pending', 'confirmed', 'checked_in')",
                    [':user_id' => $id]
                );

                if ($activeBookings[0]->count == 0) {
                    $user = $userModel->find($id);
                    if ($user) {
                        $this->deleteImage($user->profile_image);
                    }
                    if ($userModel->delete($id)) {
                        $successCount++;
                    }
                }
            }
        }

        $this->setFlash('success', "{$successCount} user berhasil diupdate");
        $this->redirect('admin/users');
    }

    /**
     * Get storage path for profile images
     */
    private function getStoragePath()
    {
        return dirname(__DIR__, 3) . '/storage/uploads/profile/';
    }

    /**
     * Upload profile image helper
     */
    private function uploadImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            $this->setFlash('error', 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WebP');
            return null;
        }

        if ($file['size'] > $maxSize) {
            $this->setFlash('error', 'Ukuran file maksimal 2MB');
            return null;
        }

        $uploadDir = $this->getStoragePath();
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'profile_' . uniqid() . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'storage/uploads/profile/' . $filename;
        }

        $this->setFlash('error', 'Gagal mengupload gambar');
        return null;
    }

    /**
     * Delete profile image helper
     */
    private function deleteImage($imagePath)
    {
        if (empty($imagePath)) {
            return false;
        }

        $fullPath = dirname(__DIR__, 3) . '/' . $imagePath;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        $userModel = $this->loadModel('User');
        
        $role = $_GET['role'] ?? null;

        if ($role && in_array($role, ['admin', 'guest'])) {
            $users = $userModel->getByRole($role);
        } else {
            $users = $userModel->all();
        }

        $filename = 'users_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Created At']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->phone,
                $user->role,
                $user->created_at
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Get user stats (AJAX)
     */
    public function stats()
    {
        $userModel = $this->loadModel('User');

        $stats = [
            'total' => $userModel->count(),
            'guests' => count($userModel->getGuests()),
            'admins' => count($userModel->getAdmins())
        ];

        // New users this month
        $newUsers = $userModel->raw(
            "SELECT COUNT(*) as count FROM users 
             WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );

        $stats['new_this_month'] = $newUsers[0]->count ?? 0;

        $this->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}