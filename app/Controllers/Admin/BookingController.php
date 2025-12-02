<?php

namespace App\Controllers\Admin;

use Core\Controller;

class BookingController extends Controller
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
     * List semua booking
     */
    public function index()
    {
        $bookingModel = $this->loadModel('Booking');
        
        // Filter by status
        $status = $_GET['status'] ?? null;
        
        if ($status && in_array($status, ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])) {
            $bookings = $bookingModel->getByStatus($status);
        } else {
            $bookings = $bookingModel->getAllWithDetails();
        }

        $this->view->setLayout('admin')->render('admin/bookings/index', [
            'title' => 'Kelola Booking - ' . APP_NAME,
            'bookings' => $bookings,
            'selectedStatus' => $status
        ]);
    }

    /**
     * Detail booking
     */
    public function detail($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->getWithDetails($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

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
            $this->redirect('admin/bookings');
        }

        $status = $_POST['status'] ?? '';
        $validStatus = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

        if (!in_array($status, $validStatus)) {
            $this->setFlash('error', 'Status tidak valid');
            $this->redirect('admin/bookings/' . $id);
        }

        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->updateStatus($id, $status)) {
            $this->setFlash('success', 'Status booking berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal mengupdate status booking');
        }

        $this->redirect('admin/bookings/' . $id);
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        if ($booking->status !== 'pending') {
            $this->setFlash('error', 'Hanya booking pending yang bisa dikonfirmasi');
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->confirm($id)) {
            $this->setFlash('success', 'Booking #' . $id . ' berhasil dikonfirmasi');
        } else {
            $this->setFlash('error', 'Gagal mengkonfirmasi booking');
        }

        $this->redirect('admin/bookings');
    }

    /**
     * Check in guest
     */
    public function checkIn($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        if ($booking->status !== 'confirmed') {
            $this->setFlash('error', 'Hanya booking confirmed yang bisa check-in');
            $this->redirect('admin/bookings');
        }

        // Validasi tanggal check-in
        $today = date('Y-m-d');
        if ($booking->check_in_date > $today) {
            $this->setFlash('error', 'Belum waktunya check-in. Tanggal check-in: ' . $booking->check_in_date);
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->checkIn($id)) {
            // Update room availability
            $roomModel = $this->loadModel('Room');
            $roomModel->setAvailability($booking->room_id, false);

            $this->setFlash('success', 'Guest berhasil check-in');
        } else {
            $this->setFlash('error', 'Gagal melakukan check-in');
        }

        $this->redirect('admin/bookings');
    }

    /**
     * Check out guest
     */
    public function checkOut($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        if ($booking->status !== 'checked_in') {
            $this->setFlash('error', 'Hanya guest yang sudah check-in yang bisa check-out');
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->checkOut($id)) {
            // Update room availability
            $roomModel = $this->loadModel('Room');
            $roomModel->setAvailability($booking->room_id, true);

            $this->setFlash('success', 'Guest berhasil check-out');
        } else {
            $this->setFlash('error', 'Gagal melakukan check-out');
        }

        $this->redirect('admin/bookings');
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            $this->setFlash('error', 'Booking ini tidak dapat dibatalkan');
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->cancel($id)) {
            $this->setFlash('success', 'Booking #' . $id . ' berhasil dibatalkan');
        } else {
            $this->setFlash('error', 'Gagal membatalkan booking');
        }

        $this->redirect('admin/bookings');
    }

    /**
     * Delete booking (permanent)
     */
    public function delete($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        // Hanya bisa delete booking yang cancelled atau checked_out
        if (!in_array($booking->status, ['cancelled', 'checked_out'])) {
            $this->setFlash('error', 'Hanya booking yang cancelled atau checked_out yang bisa dihapus');
            $this->redirect('admin/bookings');
        }

        if ($bookingModel->delete($id)) {
            $this->setFlash('success', 'Booking #' . $id . ' berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus booking');
        }

        $this->redirect('admin/bookings');
    }

    /**
     * Today's check-ins
     */
    public function todayCheckIns()
    {
        $bookingModel = $this->loadModel('Booking');
        $bookings = $bookingModel->getTodayCheckIns();

        $this->view->setLayout('admin')->render('admin/bookings/today-checkins', [
            'title' => 'Check-in Hari Ini - ' . APP_NAME,
            'bookings' => $bookings,
            'date' => date('d M Y')
        ]);
    }

    /**
     * Today's check-outs
     */
    public function todayCheckOuts()
    {
        $bookingModel = $this->loadModel('Booking');
        $bookings = $bookingModel->getTodayCheckOuts();

        $this->view->setLayout('admin')->render('admin/bookings/today-checkouts', [
            'title' => 'Check-out Hari Ini - ' . APP_NAME,
            'bookings' => $bookings,
            'date' => date('d M Y')
        ]);
    }

    /**
     * Create booking by admin
     */
    public function create()
    {
        $roomModel = $this->loadModel('Room');
        $userModel = $this->loadModel('User');

        $rooms = $roomModel->getAvailable();
        $guests = $userModel->getGuests();

        $this->view->setLayout('admin')->render('admin/bookings/create', [
            'title' => 'Buat Booking Baru - ' . APP_NAME,
            'rooms' => $rooms,
            'guests' => $guests
        ]);
    }

    /**
     * Store booking by admin
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('admin/bookings/create');
        }

        $userId = $_POST['user_id'] ?? '';
        $roomId = $_POST['room_id'] ?? '';
        $checkIn = $_POST['check_in_date'] ?? '';
        $checkOut = $_POST['check_out_date'] ?? '';
        $status = $_POST['status'] ?? 'confirmed'; // Admin bisa langsung confirm

        // Validasi input
        if (empty($userId) || empty($roomId) || empty($checkIn) || empty($checkOut)) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect('admin/bookings/create');
        }

        // Validasi tanggal
        if ($checkOut <= $checkIn) {
            $this->setFlash('error', 'Tanggal check-out harus lebih besar dari check-in');
            $this->redirect('admin/bookings/create');
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($roomId);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('admin/bookings/create');
        }

        // Cek ketersediaan
        if (!$roomModel->isAvailableForDates($roomId, $checkIn, $checkOut)) {
            $this->setFlash('error', 'Kamar tidak tersedia untuk tanggal tersebut');
            $this->redirect('admin/bookings/create');
        }

        $bookingModel = $this->loadModel('Booking');

        // Hitung total price
        $nights = $bookingModel->calculateNights($checkIn, $checkOut);
        $totalPrice = $nights * $room->price_per_night;

        // Create booking
        $bookingId = $bookingModel->create([
            'user_id' => $userId,
            'room_id' => $roomId,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_price' => $totalPrice,
            'status' => $status
        ]);

        if ($bookingId) {
            $this->setFlash('success', 'Booking berhasil dibuat');
            $this->redirect('admin/bookings/' . $bookingId);
        } else {
            $this->setFlash('error', 'Gagal membuat booking');
            $this->redirect('admin/bookings/create');
        }
    }

    /**
     * Export bookings to CSV
     */
    public function export()
    {
        $bookingModel = $this->loadModel('Booking');
        
        $status = $_GET['status'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $bookings = $bookingModel->getAllWithDetails();

        // Filter by status
        if ($status) {
            $bookings = array_filter($bookings, fn($b) => $b->status === $status);
        }

        // Filter by date range
        if ($startDate && $endDate) {
            $bookings = array_filter($bookings, function($b) use ($startDate, $endDate) {
                return $b->check_in_date >= $startDate && $b->check_in_date <= $endDate;
            });
        }

        // Generate CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="bookings_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['ID', 'Guest', 'Room', 'Check In', 'Check Out', 'Total', 'Status', 'Created']);

        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking->id,
                $booking->guest_name,
                $booking->room_number,
                $booking->check_in_date,
                $booking->check_out_date,
                $booking->total_price,
                $booking->status,
                $booking->created_at
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Print booking invoice
     */
    public function invoice($id)
    {
        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->getWithDetails($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('admin/bookings');
        }

        // Render tanpa layout untuk print
        $this->view->render('admin/bookings/invoice', [
            'title' => 'Invoice #' . $id,
            'booking' => $booking
        ]);
    }
}