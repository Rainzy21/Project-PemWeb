<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Controllers\Traits\RequiresAdmin;
use App\Controllers\Traits\HandlesImageUpload;
use App\Controllers\Traits\ValidatesUserData;
use App\Controllers\Traits\ManagesUserOperations;
use App\Controllers\Traits\FiltersUsers;
use App\Controllers\Traits\HandlesUserBulkActions;
use App\Controllers\Traits\ExportsUsers;
use App\Controllers\Traits\ExportsCsv;

class UserController extends Controller
{
    use RequiresAdmin, HandlesImageUpload, ValidatesUserData, ManagesUserOperations;
    use FiltersUsers, HandlesUserBulkActions, ExportsUsers, ExportsCsv;

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
        $params = $this->getUserFilterParams();

        $this->view->setLayout('admin')->render('admin/users/index', [
            'title' => 'Kelola Users - ' . APP_NAME,
            'users' => $this->getFilteredUsers($userModel, $params['role'], $params['search']),
            'stats' => $this->getUsersListStats($userModel),
            'selectedRole' => $params['role'],
            'searchQuery' => $params['search']
        ]);
    }

    /**
     * Detail user
     */
    public function detail($id)
    {
        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');

        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

        $this->view->setLayout('admin')->render('admin/users/detail', [
            'title' => 'Detail User - ' . APP_NAME,
            'user' => $user,
            'bookings' => $bookingModel->getByUser($id),
            'userStats' => $this->getUserStatistics($bookingModel, $id)
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
            return $this->redirect('admin/users');
        }

        $data = $this->getUserInputData();
        $_SESSION['old'] = $data;

        if (!$this->validateUserCreate($data, 'admin/users/create')) {
            return;
        }

        $userModel = $this->loadModel('User');

        if ($userModel->emailExists($data['email'])) {
            $this->setFlash('error', 'Email sudah terdaftar');
            return $this->redirect('admin/users/create');
        }

        if ($this->hasUploadedFile('profile_image')) {
            $imagePath = $this->uploadImage($_FILES['profile_image'], 'profile');
            if ($imagePath) {
                $data['profile_image'] = $imagePath;
            }
        }

        if ($userModel->register($data)) {
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
        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

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
            return $this->redirect('admin/users');
        }

        $userModel = $this->loadModel('User');
        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

        $data = $this->getUserInputData(false);
        $editUrl = "admin/users/{$id}/edit";

        if (!$this->validateUserUpdate($data, $editUrl)) {
            return;
        }

        if (!$this->isEmailUnique($userModel, $data['email'], $id)) {
            $this->setFlash('error', 'Email sudah digunakan user lain');
            return $this->redirect($editUrl);
        }

        // Prevent self role change
        if ($id == $_SESSION['user_id'] && $data['role'] !== 'admin') {
            $this->setFlash('error', 'Anda tidak dapat mengubah role sendiri');
            return $this->redirect($editUrl);
        }

        if ($this->hasUploadedFile('profile_image')) {
            $imagePath = $this->uploadImage($_FILES['profile_image'], 'profile');
            if ($imagePath) {
                $this->deleteImage($user->profile_image);
                $data['profile_image'] = $imagePath;
            }
        }

        if ($userModel->update($id, $data)) {
            $this->updateOwnSession($id, $data);
            $this->setFlash('success', 'User berhasil diupdate');
            $this->redirect('admin/users');
        } else {
            $this->setFlash('error', 'Gagal mengupdate user');
            $this->redirect($editUrl);
        }
    }

    /**
     * Reset password user
     */
    public function resetPassword($id)
    {
        if (!$this->isPost()) {
            return $this->redirect('admin/users');
        }

        $userModel = $this->loadModel('User');
        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$this->validatePasswordReset($newPassword, $confirmPassword, "admin/users/{$id}")) {
            return;
        }

        $this->setFlash(
            $userModel->updatePassword($id, $newPassword) ? 'success' : 'error',
            $userModel->updatePassword($id, $newPassword) ? 'Password berhasil direset' : 'Gagal mereset password'
        );

        $this->redirect("admin/users/{$id}");
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');

        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

        if (!$this->preventSelfModification($id, 'menghapus', 'admin/users')) {
            return;
        }

        if ($this->userHasActiveBookings($bookingModel, $id)) {
            $this->setFlash('error', 'Tidak dapat menghapus user yang memiliki booking aktif');
            return $this->redirect('admin/users');
        }

        $this->deleteImage($user->profile_image);

        $this->setFlash(
            $userModel->delete($id) ? 'success' : 'error',
            $userModel->delete($id) ? 'User berhasil dihapus' : 'Gagal menghapus user'
        );

        $this->redirect('admin/users');
    }

    /**
     * Toggle user role
     */
    public function toggleRole($id)
    {
        $userModel = $this->loadModel('User');
        $user = $this->findUserOrFail($userModel, $id);
        if (!$user) return;

        if (!$this->preventSelfModification($id, 'mengubah role', 'admin/users')) {
            return;
        }

        $newRole = $user->role === 'admin' ? 'guest' : 'admin';

        $this->setFlash(
            $userModel->update($id, ['role' => $newRole]) ? 'success' : 'error',
            $userModel->update($id, ['role' => $newRole]) 
                ? "Role user {$user->name} berhasil diubah menjadi {$newRole}" 
                : 'Gagal mengubah role user'
        );

        $this->redirect('admin/users');
    }

    /**
     * Bulk action
     */
    public function bulkAction()
    {
        if (!$this->isPost()) {
            return $this->redirect('admin/users');
        }

        $input = $this->getUserBulkInput();

        if (!$this->validateUserBulkInput($input['ids'], 'admin/users')) {
            return;
        }

        $userModel = $this->loadModel('User');
        $bookingModel = $this->loadModel('Booking');

        $successCount = match ($input['action']) {
            'make_admin' => $this->processBulkRoleChange($userModel, $input['ids'], 'admin'),
            'make_guest' => $this->processBulkRoleChange($userModel, $input['ids'], 'guest'),
            'delete' => $this->processBulkUserDelete($userModel, $bookingModel, $input['ids']),
            default => 0
        };

        $this->setFlash('success', "{$successCount} user berhasil diupdate");
        $this->redirect('admin/users');
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        $userModel = $this->loadModel('User');
        $this->exportUsersToCsv($userModel, $_GET['role'] ?? null);
    }

    /**
     * Get user stats (AJAX)
     */
    public function stats()
    {
        $userModel = $this->loadModel('User');

        $stats = $this->getUsersListStats($userModel);
        $stats['new_this_month'] = $this->getNewUsersThisMonth($userModel);

        $this->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}