<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Controllers\Traits\RequiresAdmin;
use App\Controllers\Traits\ManagesBookingStatus;
use App\Controllers\Traits\HandlesAdminBooking;
use App\Controllers\Traits\FiltersBookings;
use App\Controllers\Traits\ExportsBookings;
use App\Controllers\Traits\ExportsCsv;

class AdminBookingController extends Controller
{
    use RequiresAdmin, ManagesBookingStatus, HandlesAdminBooking;
    use FiltersBookings, ExportsBookings, ExportsCsv;

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
     * List semua booking
     */
    public function index()
    {
        $bookingModel = $this->loadModel('Booking');
        $status = $_GET['status'] ?? null;

        $this->view->setLayout('admin')->render('admin/bookings/index', [
            'title' => 'Kelola Booking - ' . APP_NAME,
            'bookings' => $this->getFilteredBookings($bookingModel, $status),
            'selectedStatus' => $status
        ]);
    }

    /**
     * Detail booking
     */
    public function detail($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingWithDetailsOrFail($bookingModel, $id);

        if (!$booking) return;

        $this->view->setLayout('admin')->render('admin/bookings/detail', [
            'title' => 'Detail Booking #' . $id . ' - ' . APP_NAME,
            'booking' => $booking
        ]);
    }

    /**
     * Update status booking
     */
    public function updateStatus($id)
    {
        if (!$this->isPost()) {
            return $this->redirect('admin/bookings');
        }

        $status = $_POST['status'] ?? '';

        if (!$this->isValidStatus($status)) {
            $this->setFlash('error', 'Status tidak valid');
            return $this->redirect("admin/bookings/{$id}");
        }

        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        $this->setFlash(
            $bookingModel->updateStatus($id, $status) ? 'success' : 'error',
            $bookingModel->updateStatus($id, $status) 
                ? 'Status booking berhasil diupdate' 
                : 'Gagal mengupdate status booking'
        );

        $this->redirect("admin/bookings/{$id}");
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        if (!$this->canConfirm($booking)) {
            $this->setFlash('error', $this->getStatusErrorMessage('confirm', $booking));
            return $this->redirect('admin/bookings');
        }

        $this->setFlash(
            $bookingModel->confirm($id) ? 'success' : 'error',
            $bookingModel->confirm($id) 
                ? "Booking #{$id} berhasil dikonfirmasi" 
                : 'Gagal mengkonfirmasi booking'
        );

        $this->redirect('admin/bookings');
    }

    /**
     * Check in guest
     */
    public function checkIn($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $roomModel = $this->loadModel('Room');

        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        if (!$this->canCheckIn($booking)) {
            $this->setFlash('error', $this->getStatusErrorMessage('check_in', $booking));
            return $this->redirect('admin/bookings');
        }

        $this->setFlash(
            $this->processCheckIn($bookingModel, $roomModel, $booking, $id) ? 'success' : 'error',
            $this->processCheckIn($bookingModel, $roomModel, $booking, $id) 
                ? 'Guest berhasil check-in' 
                : 'Gagal melakukan check-in'
        );

        $this->redirect('admin/bookings');
    }

    /**
     * Check out guest
     */
    public function checkOut($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $roomModel = $this->loadModel('Room');

        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        if (!$this->canCheckOut($booking)) {
            $this->setFlash('error', $this->getStatusErrorMessage('check_out', $booking));
            return $this->redirect('admin/bookings');
        }

        $this->setFlash(
            $this->processCheckOut($bookingModel, $roomModel, $booking, $id) ? 'success' : 'error',
            $this->processCheckOut($bookingModel, $roomModel, $booking, $id) 
                ? 'Guest berhasil check-out' 
                : 'Gagal melakukan check-out'
        );

        $this->redirect('admin/bookings');
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        if (!$this->canCancel($booking)) {
            $this->setFlash('error', $this->getStatusErrorMessage('cancel', $booking));
            return $this->redirect('admin/bookings');
        }

        $this->setFlash(
            $bookingModel->cancel($id) ? 'success' : 'error',
            $bookingModel->cancel($id) 
                ? "Booking #{$id} berhasil dibatalkan" 
                : 'Gagal membatalkan booking'
        );

        $this->redirect('admin/bookings');
    }

    /**
     * Delete booking
     */
    public function delete($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingOrFail($bookingModel, $id);

        if (!$booking) return;

        if (!$this->canDelete($booking)) {
            $this->setFlash('error', $this->getStatusErrorMessage('delete', $booking));
            return $this->redirect('admin/bookings');
        }

        $this->setFlash(
            $bookingModel->delete($id) ? 'success' : 'error',
            $bookingModel->delete($id) 
                ? "Booking #{$id} berhasil dihapus" 
                : 'Gagal menghapus booking'
        );

        $this->redirect('admin/bookings');
    }

    /**
     * Today's check-ins
     */
    public function todayCheckIns()
    {
        $bookingModel = $this->loadModel('Booking');

        $this->view->setLayout('admin')->render('admin/bookings/today-checkins', [
            'title' => 'Check-in Hari Ini - ' . APP_NAME,
            'bookings' => $bookingModel->getTodayCheckIns(),
            'date' => date('d M Y')
        ]);
    }

    /**
     * Today's check-outs
     */
    public function todayCheckOuts()
    {
        $bookingModel = $this->loadModel('Booking');

        $this->view->setLayout('admin')->render('admin/bookings/today-checkouts', [
            'title' => 'Check-out Hari Ini - ' . APP_NAME,
            'bookings' => $bookingModel->getTodayCheckOuts(),
            'date' => date('d M Y')
        ]);
    }

    /**
     * Create booking form
     */
    public function create()
    {
        $roomModel = $this->loadModel('Room');
        $userModel = $this->loadModel('User');

        $this->view->setLayout('admin')->render('admin/bookings/form', [
            'title' => 'Buat Booking Baru - ' . APP_NAME,
            'rooms' => $roomModel->getAvailable(),
            'guests' => $userModel->getGuests(),
            'action' => 'create',
            'booking' => null
        ]);
    }

    /**
     * Store booking
     */
    public function store()
    {
        if (!$this->isPost()) {
            return $this->redirect('admin/bookings/create');
        }

        $input = $this->getAdminBookingInput();

        if (!$this->validateAdminBookingInput($input, 'admin/bookings/create')) {
            return;
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($input['room_id']);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            return $this->redirect('admin/bookings/create');
        }

        if (!$roomModel->isAvailableForDates($input['room_id'], $input['check_in'], $input['check_out'])) {
            $this->setFlash('error', 'Kamar tidak tersedia untuk tanggal tersebut');
            return $this->redirect('admin/bookings/create');
        }

        $bookingModel = $this->loadModel('Booking');
        $nights = $bookingModel->calculateNights($input['check_in'], $input['check_out']);

        $bookingId = $bookingModel->create([
            'user_id' => $input['user_id'],
            'room_id' => $input['room_id'],
            'check_in_date' => $input['check_in'],
            'check_out_date' => $input['check_out'],
            'total_price' => $nights * $room->price_per_night,
            'status' => $input['status']
        ]);

        if ($bookingId) {
            $this->setFlash('success', 'Booking berhasil dibuat');
            $this->redirect("admin/bookings/{$bookingId}");
        } else {
            $this->setFlash('error', 'Gagal membuat booking');
            $this->redirect('admin/bookings/create');
        }
    }

    /**
     * Export bookings
     */
    public function export()
    {
        $bookingModel = $this->loadModel('Booking');
        $this->exportBookingsToCsv($bookingModel, $this->getBookingFilterParams());
    }

    /**
     * Print invoice
     */
    public function invoice($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingWithDetailsOrFail($bookingModel, $id);

        if (!$booking) return;

        $this->view->render('admin/bookings/invoice', [
            'title' => 'Invoice #' . $id,
            'booking' => $booking
        ]);
    }
}