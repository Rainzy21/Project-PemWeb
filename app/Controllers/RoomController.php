<?php

namespace App\Controllers;

use Core\Controller;

class RoomController extends Controller
{
    /**
     * Tampilkan semua kamar
     */
    public function index()
    {
        $roomModel = $this->loadModel('Room');

        // Filter berdasarkan query string
        $type = $_GET['type'] ?? null;
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;

        if ($type && in_array($type, ['standard', 'deluxe', 'suite'])) {
            $rooms = $roomModel->getByType($type);
        } elseif ($minPrice && $maxPrice) {
            $rooms = $roomModel->getByPriceRange($minPrice, $maxPrice);
        } else {
            $rooms = $roomModel->getAvailable();
        }

        $this->view->setLayout('main')->render('rooms/index', [
            'title' => 'Kamar Tersedia - ' . APP_NAME,
            'rooms' => $rooms,
            'selectedType' => $type,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }

    /**
     * Tampilkan detail kamar
     */
    public function detail($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->setFlash('error', 'Kamar tidak ditemukan');
            $this->redirect('rooms');
        }

        // Ambil kamar serupa (tipe sama)
        $similarRooms = $roomModel->getByType($room->room_type);
        $similarRooms = array_filter($similarRooms, fn($r) => $r->id != $room->id);
        $similarRooms = array_slice($similarRooms, 0, 3);

        $this->view->setLayout('main')->render('rooms/detail', [
            'title' => 'Kamar ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room,
            'similarRooms' => $similarRooms
        ]);
    }

    /**
     * Search kamar berdasarkan tanggal
     */
    public function search()
    {
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';
        $type = $_GET['type'] ?? null;

        if (empty($checkIn) || empty($checkOut)) {
            $this->setFlash('error', 'Tanggal check-in dan check-out harus diisi');
            $this->redirect('rooms');
        }

        // Validasi tanggal
        $today = date('Y-m-d');
        if ($checkIn < $today) {
            $this->setFlash('error', 'Tanggal check-in tidak boleh kurang dari hari ini');
            $this->redirect('rooms');
        }

        if ($checkOut <= $checkIn) {
            $this->setFlash('error', 'Tanggal check-out harus lebih besar dari check-in');
            $this->redirect('rooms');
        }

        $roomModel = $this->loadModel('Room');
        
        // Ambil semua kamar yang tersedia
        if ($type && in_array($type, ['standard', 'deluxe', 'suite'])) {
            $allRooms = $roomModel->getByType($type);
        } else {
            $allRooms = $roomModel->getAvailable();
        }

        // Filter kamar yang tersedia untuk tanggal tersebut
        $availableRooms = [];
        foreach ($allRooms as $room) {
            if ($roomModel->isAvailableForDates($room->id, $checkIn, $checkOut)) {
                $availableRooms[] = $room;
            }
        }

        $this->view->setLayout('main')->render('rooms/search', [
            'title' => 'Hasil Pencarian - ' . APP_NAME,
            'rooms' => $availableRooms,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'selectedType' => $type
        ]);
    }

    /**
     * Filter kamar by type (AJAX)
     */
    public function filterByType()
    {
        $type = $_GET['type'] ?? '';
        $roomModel = $this->loadModel('Room');

        if ($type && in_array($type, ['standard', 'deluxe', 'suite'])) {
            $rooms = $roomModel->getByType($type);
        } else {
            $rooms = $roomModel->getAvailable();
        }

        $this->json([
            'success' => true,
            'rooms' => $rooms,
            'count' => count($rooms)
        ]);
    }

    /**
     * Get room info (AJAX)
     */
    public function getInfo($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $roomModel->find($id);

        if (!$room) {
            $this->json(['error' => 'Kamar tidak ditemukan'], 404);
        }

        $this->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->room_type,
                'price_per_night' => $room->price_per_night,
                'price_formatted' => 'Rp ' . number_format($room->price_per_night, 0, ',', '.'),
                'description' => $room->description,
                'image' => $room->image,
                'is_available' => $room->is_available
            ]
        ]);
    }

    /**
     * Check availability (AJAX)
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

        // Hitung total harga
        $bookingModel = $this->loadModel('Booking');
        $nights = $bookingModel->calculateNights($checkIn, $checkOut);
        $totalPrice = $nights * $room->price_per_night;

        $this->json([
            'success' => true,
            'available' => $isAvailable,
            'room_number' => $room->room_number,
            'nights' => $nights,
            'price_per_night' => $room->price_per_night,
            'total_price' => $totalPrice,
            'price_formatted' => 'Rp ' . number_format($room->price_per_night, 0, ',', '.'),
            'total_formatted' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ]);
    }

    /**
     * Get all room types
     */
    public function types()
    {
        $roomModel = $this->loadModel('Room');

        $types = [
            'standard' => [
                'name' => 'Standard',
                'description' => 'Kamar standar dengan fasilitas dasar yang nyaman',
                'rooms' => $roomModel->getStandard()
            ],
            'deluxe' => [
                'name' => 'Deluxe',
                'description' => 'Kamar deluxe dengan fasilitas lebih lengkap',
                'rooms' => $roomModel->getDeluxe()
            ],
            'suite' => [
                'name' => 'Suite',
                'description' => 'Kamar suite mewah dengan fasilitas premium',
                'rooms' => $roomModel->getSuite()
            ]
        ];

        $this->view->setLayout('main')->render('rooms/types', [
            'title' => 'Tipe Kamar - ' . APP_NAME,
            'types' => $types
        ]);
    }
}