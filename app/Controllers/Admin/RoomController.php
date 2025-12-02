<?php

namespace App\Controllers\Admin;

use Core\Controller;

class RoomController extends Controller
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
     * List semua kamar
     */
    public function index()
    {
        $roomModel = $this->loadModel('Room');

        // Filter
        $type = $_GET['type'] ?? null;
        $status = $_GET['status'] ?? null;

        if ($type && in_array($type, ['standard', 'deluxe', 'suite'])) {
            $rooms = $roomModel->getByType($type);
        } elseif ($status === 'available') {
            $rooms = $roomModel->getAvailable();
        } elseif ($status === 'occupied') {
            $rooms = $roomModel->raw(
                "SELECT * FROM rooms WHERE is_available = 0"
            );
        } else {
            $rooms = $roomModel->all();
        }

        // Statistics
        $stats = [
            'total' => $roomModel->count(),
            'available' => count($roomModel->getAvailable()),
            'standard' => count($roomModel->getStandard()),
            'deluxe' => count($roomModel->getDeluxe()),
            'suite' => count($roomModel->getSuite())
        ];

        $this->view->setLayout('admin')->render('admin/rooms/index', [
            'title' => 'Kelola Kamar - ' . APP_NAME,
            'rooms' => $rooms,
            'stats' => $stats,
            'selectedType' => $type,
            'selectedStatus' => $status
        ]);
    }

    /**
     * Form tambah kamar
     */
    public function create()
    {
        $this->view->setLayout('admin')->render('admin/rooms/form', [
            'title' => 'Tambah Kamar - ' . APP_NAME,
            'room' => null,
            'action' => 'create'
        ]);
    }

    /**
     * Simpan kamar baru
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('admin/rooms');
        }

        $data = [
            'room_number' => trim($_POST['room_number'] ?? ''),
            'room_type' => $_POST['room_type'] ?? 'standard',
            'price_per_night' => floatval($_POST['price_per_night'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];

        // Validasi
        $errors = $this->validate($data, [
            'room_number' => 'required',
            'room_type' => 'required',
            'price_per_night' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $_SESSION['old'] = $data;
            $this->redirect('admin/rooms/create');
        }

        // Validasi room type
        if (!in_array($data['room_type'], ['standard', 'deluxe', 'suite'])) {
            $this->setFlash('error', 'Tipe kamar tidak valid');
            $this->redirect('admin/rooms/create');
        }

        // Validasi price
        if ($data['price_per_night'] <= 0) {
            $this->setFlash('error', 'Harga harus lebih dari 0');
            $this->redirect('admin/rooms/create');
        }

        $roomModel = $this->loadModel('Room');

        // Cek room number sudah ada
        if ($roomModel->findByRoomNumber($data['room_number'])) {
            $this->setFlash('error', 'Nomor kamar sudah digunakan');
            $this->redirect('admin/rooms/create');
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image']);
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        if ($roomModel->create($data)) {
            unset($_SESSION['old']);
            $this->setFlash('success', 'Kamar berhasil ditambahkan');
            $this->redirect('admin/rooms');
        } else {
            $this->setFlash('error', 'Gagal menambahkan kamar');
            $this->redirect('admin/rooms/create');
        }
    }

    /**
     * Detail kamar
     */
    public function detail($id)
    {
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/rooms');
        }

        // Get booking history for this room
        $bookings = $bookingModel->raw(
            "SELECT b.*, u.name as guest_name
             FROM bookings b
             JOIN users u ON b.user_id = u.id
             WHERE b.room_id = :room_id
             ORDER BY b.created_at DESC
             LIMIT 10",
            [':room_id' => $id]
        );

        // Room statistics
        $roomStats = $bookingModel->raw(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as total_revenue,
                SUM(DATEDIFF(check_out_date, check_in_date)) as total_nights
             FROM bookings
             WHERE room_id = :room_id
             AND status IN ('checked_in', 'checked_out')",
            [':room_id' => $id]
        );

        $this->view->setLayout('admin')->render('admin/rooms/detail', [
            'title' => 'Detail Kamar ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room,
            'bookings' => $bookings,
            'roomStats' => $roomStats[0] ?? null
        ]);
    }

    /**
     * Form edit kamar
     */
    public function edit($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/rooms');
        }

        $this->view->setLayout('admin')->render('admin/rooms/form', [
            'title' => 'Edit Kamar ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room,
            'action' => 'edit'
        ]);
    }

    /**
     * Update kamar
     */
    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('admin/rooms');
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/rooms');
        }

        $data = [
            'room_number' => trim($_POST['room_number'] ?? ''),
            'room_type' => $_POST['room_type'] ?? 'standard',
            'price_per_night' => floatval($_POST['price_per_night'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];

        // Validasi
        $errors = $this->validate($data, [
            'room_number' => 'required',
            'room_type' => 'required',
            'price_per_night' => 'required'
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/rooms/' . $id . '/edit');
        }

        // Validasi room type
        if (!in_array($data['room_type'], ['standard', 'deluxe', 'suite'])) {
            $this->setFlash('error', 'Tipe kamar tidak valid');
            $this->redirect('admin/rooms/' . $id . '/edit');
        }

        // Cek room number sudah ada (kecuali room ini sendiri)
        $existingRoom = $roomModel->findByRoomNumber($data['room_number']);
        if ($existingRoom && $existingRoom->id != $id) {
            $this->setFlash('error', 'Nomor kamar sudah digunakan');
            $this->redirect('admin/rooms/' . $id . '/edit');
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image']);
            if ($imagePath) {
                // Delete old image
                $this->deleteImage($room->image);
                $data['image'] = $imagePath;
            }
        }

        if ($roomModel->update($id, $data)) {
            $this->setFlash('success', 'Kamar berhasil diupdate');
            $this->redirect('admin/rooms');
        } else {
            $this->setFlash('error', 'Gagal mengupdate kamar');
            $this->redirect('admin/rooms/' . $id . '/edit');
        }
    }

    /**
     * Delete kamar
     */
    public function delete($id)
    {
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/rooms');
        }

        // Cek apakah ada booking aktif
        $activeBookings = $bookingModel->raw(
            "SELECT COUNT(*) as count FROM bookings 
             WHERE room_id = :room_id 
             AND status IN ('pending', 'confirmed', 'checked_in')",
            [':room_id' => $id]
        );

        if ($activeBookings[0]->count > 0) {
            $this->setFlash('error', 'Tidak dapat menghapus kamar yang memiliki booking aktif');
            $this->redirect('admin/rooms');
        }

        // Delete image
        $this->deleteImage($room->image);

        if ($roomModel->delete($id)) {
            $this->setFlash('success', 'Kamar berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus kamar');
        }

        $this->redirect('admin/rooms');
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/rooms');
        }

        $newStatus = !$room->is_available;

        if ($roomModel->setAvailability($id, $newStatus)) {
            $status = $newStatus ? 'tersedia' : 'tidak tersedia';
            $this->setFlash('success', "Kamar {$room->room_number} sekarang {$status}");
        } else {
            $this->setFlash('error', 'Gagal mengubah status kamar');
        }

        $this->redirect('admin/rooms');
    }

    /**
     * Bulk update availability
     */
    public function bulkUpdate()
    {
        if (!$this->isPost()) {
            $this->redirect('admin/rooms');
        }

        $action = $_POST['action'] ?? '';
        $roomIds = $_POST['room_ids'] ?? [];

        if (empty($roomIds)) {
            $this->setFlash('error', 'Pilih minimal satu kamar');
            $this->redirect('admin/rooms');
        }

        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');
        $successCount = 0;

        foreach ($roomIds as $id) {
            if ($action === 'set_available') {
                if ($roomModel->setAvailability($id, true)) {
                    $successCount++;
                }
            } elseif ($action === 'set_unavailable') {
                if ($roomModel->setAvailability($id, false)) {
                    $successCount++;
                }
            } elseif ($action === 'delete') {
                // Check for active bookings first
                $activeBookings = $bookingModel->raw(
                    "SELECT COUNT(*) as count FROM bookings 
                     WHERE room_id = :room_id 
                     AND status IN ('pending', 'confirmed', 'checked_in')",
                    [':room_id' => $id]
                );

                if ($activeBookings[0]->count == 0) {
                    $room = $roomModel->find($id);
                    if ($room) {
                        $this->deleteImage($room->image);
                    }
                    if ($roomModel->delete($id)) {
                        $successCount++;
                    }
                }
            }
        }

        $this->setFlash('success', "{$successCount} kamar berhasil diupdate");
        $this->redirect('admin/rooms');
    }

    /**
     * Get storage path for room images
     */
    private function getStoragePath()
    {
        return dirname(__DIR__, 3) . '/storage/uploads/rooms/';
    }

    /**
     * Upload image helper
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
        $filename = 'room_' . uniqid() . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'storage/uploads/rooms/' . $filename;
        }

        $this->setFlash('error', 'Gagal mengupload gambar');
        return null;
    }

    /**
     * Delete room image helper
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
     * Check room availability (AJAX)
     */
    public function checkAvailability($id)
    {
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';

        if (empty($checkIn) || empty($checkOut)) {
            $this->json(['error' => 'Tanggal harus diisi'], 400);
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->json(['error' => 'Kamar tidak ditemukan'], 404);
        }

        $isAvailable = $roomModel->isAvailableForDates($id, $checkIn, $checkOut);

        $this->json([
            'success' => true,
            'room_number' => $room->room_number,
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Kamar tersedia' : 'Kamar tidak tersedia untuk tanggal tersebut'
        ]);
    }

    /**
     * Get room statistics (AJAX)
     */
    public function stats()
    {
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $stats = [
            'total' => $roomModel->count(),
            'available' => count($roomModel->getAvailable()),
            'occupied' => $roomModel->count() - count($roomModel->getAvailable()),
            'by_type' => [
                'standard' => count($roomModel->getStandard()),
                'deluxe' => count($roomModel->getDeluxe()),
                'suite' => count($roomModel->getSuite())
            ]
        ];

        $this->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}