<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Controllers\Traits\RequiresAdmin;
use App\Controllers\Traits\HandlesRoom;
use App\Controllers\Traits\HandlesImageUpload;
use App\Controllers\Traits\ValidatesRoomData;
use App\Controllers\Traits\ManagesRoomOperations;
use App\Controllers\Traits\HandlesBulkOperations;

class RoomController extends Controller
{
    use RequiresAdmin, HandlesRoom, HandlesImageUpload, ValidatesRoomData, ManagesRoomOperations, HandlesBulkOperations;

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
     * List semua kamar
     */
    public function index()
    {
        $roomModel = $this->loadModel('Room');
        $type = $_GET['type'] ?? null;
        $status = $_GET['status'] ?? null;

        $this->view->setLayout('admin')->render('admin/rooms/index', [
            'title' => 'Kelola Kamar - ' . APP_NAME,
            'rooms' => $this->getFilteredRoomsAdmin($roomModel, $type, $status),
            'stats' => $this->getRoomsListStats($roomModel),
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
            return $this->redirect('admin/rooms');
        }

        $data = $this->getRoomInputData();

        if (!$this->validateRoomData($data, 'admin/rooms/create')) {
            return;
        }

        $roomModel = $this->loadModel('Room');

        if (!$this->isRoomNumberUnique($roomModel, $data['room_number'])) {
            $this->setFlash('error', 'Nomor kamar sudah digunakan');
            return $this->redirect('admin/rooms/create');
        }

        if ($this->hasUploadedFile('image')) {
            $imagePath = $this->uploadImage($_FILES['image'], 'rooms');
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

        $room = $this->findRoomOrFail($roomModel, $id, 'admin/rooms');
        if (!$room) return;

        $this->view->setLayout('admin')->render('admin/rooms/detail', [
            'title' => 'Detail Kamar ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room,
            'bookings' => $this->getRoomBookingHistory($bookingModel, $id),
            'roomStats' => $this->getRoomStatistics($bookingModel, $id)
        ]);
    }

    /**
     * Form edit kamar
     */
    public function edit($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id, 'admin/rooms');
        if (!$room) return;

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
            return $this->redirect('admin/rooms');
        }

        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id, 'admin/rooms');
        if (!$room) return;

        $data = $this->getRoomInputData();
        $editUrl = "admin/rooms/{$id}/edit";

        if (!$this->validateRoomData($data, $editUrl)) {
            return;
        }

        if (!$this->isRoomNumberUnique($roomModel, $data['room_number'], $id)) {
            $this->setFlash('error', 'Nomor kamar sudah digunakan');
            return $this->redirect($editUrl);
        }

        if ($this->hasUploadedFile('image')) {
            $imagePath = $this->uploadImage($_FILES['image'], 'rooms');
            if ($imagePath) {
                $this->deleteImage($room->image);
                $data['image'] = $imagePath;
            }
        }

        if ($roomModel->update($id, $data)) {
            $this->setFlash('success', 'Kamar berhasil diupdate');
            $this->redirect('admin/rooms');
        } else {
            $this->setFlash('error', 'Gagal mengupdate kamar');
            $this->redirect($editUrl);
        }
    }

    /**
     * Delete kamar
     */
    public function delete($id)
    {
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $room = $this->findRoomOrFail($roomModel, $id, 'admin/rooms');
        if (!$room) return;

        if ($this->hasActiveBookings($bookingModel, $id)) {
            $this->setFlash('error', 'Tidak dapat menghapus kamar yang memiliki booking aktif');
            return $this->redirect('admin/rooms');
        }

        $this->deleteImage($room->image);

        $this->setFlash(
            $roomModel->delete($id) ? 'success' : 'error',
            $roomModel->delete($id) ? 'Kamar berhasil dihapus' : 'Gagal menghapus kamar'
        );

        $this->redirect('admin/rooms');
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id, 'admin/rooms');
        if (!$room) return;

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
     * Bulk update
     */
    public function bulkUpdate()
    {
        if (!$this->isPost()) {
            return $this->redirect('admin/rooms');
        }

        $input = $this->getBulkActionInput();

        if (!$this->validateBulkInput($input['ids'], 'admin/rooms')) {
            return;
        }

        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $successCount = match ($input['action']) {
            'set_available' => $this->processBulkAvailability($roomModel, $input['ids'], true),
            'set_unavailable' => $this->processBulkAvailability($roomModel, $input['ids'], false),
            'delete' => $this->processBulkDelete($roomModel, $bookingModel, $input['ids']),
            default => 0
        };

        $this->setFlash('success', "{$successCount} kamar berhasil diupdate");
        $this->redirect('admin/rooms');
    }

    /**
     * Check room availability (AJAX)
     */
    public function checkAvailability($id)
    {
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';

        if (empty($checkIn) || empty($checkOut)) {
            return $this->json(['error' => 'Tanggal harus diisi'], 400);
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            return $this->json(['error' => 'Kamar tidak ditemukan'], 404);
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

        $this->json([
            'success' => true,
            'stats' => [
                'total' => $roomModel->count(),
                'available' => count($roomModel->getAvailable()),
                'occupied' => $roomModel->count() - count($roomModel->getAvailable()),
                'by_type' => [
                    'standard' => count($roomModel->getStandard()),
                    'deluxe' => count($roomModel->getDeluxe()),
                    'suite' => count($roomModel->getSuite())
                ]
            ]
        ]);
    }
}