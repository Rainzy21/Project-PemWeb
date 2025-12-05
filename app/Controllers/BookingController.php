<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesBooking;
use App\Controllers\Traits\HandlesRoom;
use App\Controllers\Traits\ValidatesBookingDates;

class BookingController extends Controller
{
    use HandlesBooking, HandlesRoom, ValidatesBookingDates;

    /**
     * Tampilkan form booking untuk room tertentu
     */
    public function create($id)  // Changed from $roomId to $id
    {
        $this->requireLogin();

        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id);  // Changed from $roomId to $id
        
        if (!$room || !$this->ensureRoomAvailable($room)) {
            return;
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
            return $this->redirect('rooms');
        }

        $input = $this->getBookingInput();
        $redirectTo = 'booking/create/' . $input['room_id'];  // Fixed: tambah 'create/'

        // Validasi input & tanggal
        if (!$this->validateBookingInput($input, $redirectTo)) return;
        if (!$this->validateBookingDates($input['check_in'], $input['check_out'], $redirectTo)) return;

        // Validasi room
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $input['room_id']);
        if (!$room) return;

        // Cek ketersediaan
        if (!$this->ensureRoomAvailableForDates($roomModel, $input['room_id'], $input['check_in'], $input['check_out'], $redirectTo)) {
            return;
        }

        // Proses booking
        $this->processBooking($input, $room);
    }

    /**
     * Process the actual booking creation
     */
    protected function processBooking(array $input, object $room): void
    {
        $bookingModel = $this->loadModel('Booking');

        // Calculate total price
        $totalPrice = $bookingModel->calculateTotalPrice(
            $room->price_per_night,
            $input['check_in'],
            $input['check_out']
        );

        // Create booking using the model's create method
        $bookingId = $bookingModel->create([
            'user_id' => $_SESSION['user_id'],
            'room_id' => $input['room_id'],
            'check_in_date' => $input['check_in'],
            'check_out_date' => $input['check_out'],
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        if ($bookingId) {
            unset($_SESSION['old']);
            $this->setFlash('success', 'Booking berhasil! Silakan tunggu konfirmasi');
            $this->redirect('my-bookings');
        } else {
            $this->setFlash('error', 'Booking gagal. Silakan coba lagi');
            $this->redirect('booking/create/' . $input['room_id']);
        }
    }

    /**
     * Tampilkan booking user yang login
     */
    public function myBookings()
    {
        $this->requireLogin();

        $bookings = $this->loadModel('Booking')->getByUser($_SESSION['user_id']);

        $this->view->setLayout('main')->render('home/my-bookings', [
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
        $booking = $this->findBookingWithDetailsOrFail($bookingModel, $id);
        
        if (!$booking) return;

        if (!$this->validateBookingOwnership($booking)) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke booking ini');
            return $this->redirect('my-bookings');
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
        $booking = $this->findBookingOrFail($bookingModel, $id);
        
        if (!$booking) return;

        if (!$this->validateBookingOwnership($booking, false)) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke booking ini');
            return $this->redirect('my-bookings');
        }

        if (!$this->isCancellable($booking)) {
            $this->setFlash('error', 'Booking tidak dapat dibatalkan');
            return $this->redirect('my-bookings');
        }

        $this->setFlash(
            $bookingModel->cancel($id) ? 'success' : 'error',
            $bookingModel->cancel($id) ? 'Booking berhasil dibatalkan' : 'Gagal membatalkan booking'
        );

        $this->redirect('my-bookings');
    }

    /**
     * Cek ketersediaan kamar (AJAX)
     */
    public function checkAvailability()
    {
        if (!$this->isPost()) {
            return $this->json(['error' => 'Invalid request'], 400);
        }

        $input = $this->getBookingInput();

        if (empty($input['room_id']) || empty($input['check_in']) || empty($input['check_out'])) {
            return $this->json(['error' => 'Data tidak lengkap'], 400);
        }

        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($input['room_id']);

        if (!$room) {
            return $this->json(['error' => 'Kamar tidak ditemukan'], 404);
        }

        $this->json($this->buildAvailabilityResponse($roomModel, $room, $input));
    }

    /**
     * Build availability check response
     */
    protected function buildAvailabilityResponse($roomModel, object $room, array $input): array
    {
        // Cek is_available DAN tidak ada booking yang overlap
        $isAvailable = $room->is_available && $roomModel->isAvailableForDates($input['room_id'], $input['check_in'], $input['check_out']);

        $bookingModel = $this->loadModel('Booking');
        $nights = $bookingModel->calculateNights($input['check_in'], $input['check_out']);
        $totalPrice = $nights * $room->price_per_night;

        return [
            'available' => $isAvailable,
            'nights' => $nights,
            'price_per_night' => $room->price_per_night,
            'total_price' => $totalPrice,
            'total_price_formatted' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ];
    }
}