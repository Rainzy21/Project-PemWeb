<?php

namespace App\Controllers;

use Core\Controller;

class BookingController extends Controller
{
    /**
     * Tampilkan form booking untuk room tertentu
     */
    public function create($roomId)
    {
        $this->requireLogin();

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($roomId);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('rooms');
        }

        if (!$room->is_available) {
            $this->setFlash('error', 'Kamar tidak tersedia');
            $this->redirect('rooms');
        }

        $this->view->setLayout('main')->render('booking/create', [
            'title' => 'Booking ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room
        ]);
    }

    /**
     * Proses booking
     */
    public function store()
    {
        $this->requireLogin();

        if (!$this->isPost()) {
            $this->redirect('rooms');
        }

        $roomId = $_POST['room_id'] ?? '';
        $checkIn = $_POST['check_in_date'] ?? '';
        $checkOut = $_POST['check_out_date'] ?? '';

        // Validasi input
        if (empty($roomId) || empty($checkIn) || empty($checkOut)) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect('booking/' . $roomId);
        }

        // Validasi tanggal
        $today = date('Y-m-d');
        if ($checkIn < $today) {
            $this->setFlash('error', 'Tanggal check-in tidak boleh kurang dari hari ini');
            $this->redirect('booking/' . $roomId);
        }

        if ($checkOut <= $checkIn) {
            $this->setFlash('error', 'Tanggal check-out harus lebih besar dari check-in');
            $this->redirect('booking/' . $roomId);
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($roomId);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('rooms');
        }

        // Cek ketersediaan kamar untuk tanggal tersebut
        if (!$roomModel->isAvailableForDates($roomId, $checkIn, $checkOut)) {
            $this->setFlash('error', 'Kamar tidak tersedia untuk tanggal tersebut');
            $this->redirect('booking/' . $roomId);
        }

        $bookingModel = $this->loadModel('Booking');

        // Buat booking
        $bookingId = $bookingModel->createBooking(
            $_SESSION['user_id'],
            $roomId,
            $checkIn,
            $checkOut,
            $room->price_per_night
        );

        if ($bookingId) {
            $this->setFlash('success', 'Booking berhasil! Silakan tunggu konfirmasi');
            $this->redirect('my-bookings');
        } else {
            $this->setFlash('error', 'Booking gagal. Silakan coba lagi');
            $this->redirect('booking/' . $roomId);
        }
    }

    /**
     * Tampilkan booking user yang login
     */
    public function myBookings()
    {
        $this->requireLogin();

        $bookingModel = $this->loadModel('Booking');
        $bookings = $bookingModel->getByUser($_SESSION['user_id']);

        $this->view->setLayout('main')->render('booking/my-bookings', [
            'title' => 'Booking Saya - ' . APP_NAME,
            'bookings' => $bookings
        ]);
    }

    /**
     * Tampilkan detail booking
     */
    public function detail($id)
    {
        $this->requireLogin();

        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->getWithDetails($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('my-bookings');
        }

        // Pastikan booking milik user yang login (kecuali admin)
        if ($booking->user_id != $_SESSION['user_id'] && $_SESSION['user']->role !== 'admin') {
            $this->setFlash('error', 'Anda tidak memiliki akses ke booking ini');
            $this->redirect('my-bookings');
        }

        $this->view->setLayout('main')->render('booking/detail', [
            'title' => 'Detail Booking #' . $id . ' - ' . APP_NAME,
            'booking' => $booking
        ]);
    }

    /**
     * Cancel booking oleh user
     */
    public function cancel($id)
    {
        $this->requireLogin();

        $bookingModel = $this->loadModel('Booking');
        $booking = $bookingModel->find($id);

        if (!$booking) {
            $this->setFlash('error', 'Booking tidak ditemukan');
            $this->redirect('my-bookings');
        }

        // Pastikan booking milik user yang login
        if ($booking->user_id != $_SESSION['user_id']) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke booking ini');
            $this->redirect('my-bookings');
        }

        // Hanya bisa cancel jika status pending atau confirmed
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            $this->setFlash('error', 'Booking tidak dapat dibatalkan');
            $this->redirect('my-bookings');
        }

        if ($bookingModel->cancel($id)) {
            $this->setFlash('success', 'Booking berhasil dibatalkan');
        } else {
            $this->setFlash('error', 'Gagal membatalkan booking');
        }

        $this->redirect('my-bookings');
    }

    /**
     * Cek ketersediaan kamar (AJAX)
     */
    public function checkAvailability()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Invalid request'], 400);
        }

        $roomId = $_POST['room_id'] ?? '';
        $checkIn = $_POST['check_in_date'] ?? '';
        $checkOut = $_POST['check_out_date'] ?? '';

        if (empty($roomId) || empty($checkIn) || empty($checkOut)) {
            $this->json(['error' => 'Data tidak lengkap'], 400);
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($roomId);

        if (!$room) {
            $this->json(['error' => 'Kamar tidak ditemukan'], 404);
        }

        $isAvailable = $roomModel->isAvailableForDates($roomId, $checkIn, $checkOut);

        $bookingModel = $this->loadModel('Booking');
        $nights = $bookingModel->calculateNights($checkIn, $checkOut);
        $totalPrice = $nights * $room->price_per_night;

        $this->json([
            'available' => $isAvailable,
            'nights' => $nights,
            'price_per_night' => $room->price_per_night,
            'total_price' => $totalPrice,
            'total_price_formatted' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ]);
    }
}