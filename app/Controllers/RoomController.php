<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesRoom;
use App\Controllers\Traits\HandlesRoomFilter;
use App\Controllers\Traits\FormatsRoomData;
use App\Controllers\Traits\ValidatesBookingDates;

class RoomController extends Controller
{
    use HandlesRoom, HandlesRoomFilter, FormatsRoomData, ValidatesBookingDates;

    /**
     * Tampilkan semua kamar
     */
    public function index()
    {
        $roomModel = $this->loadModel('Room');
        $params = $this->getFilterParams();
        $rooms = $this->getFilteredRooms($roomModel, $params);

        $this->view->setLayout('main')->render('rooms/index', [
            'title' => 'Kamar Tersedia - ' . APP_NAME,
            'rooms' => $rooms,
            'selectedType' => $params['type'],
            'minPrice' => $params['min_price'],
            'maxPrice' => $params['max_price'],
            'search' => $params['search'] ?? '',
            'checkIn' => $params['check_in'] ?? '',
            'checkOut' => $params['check_out'] ?? ''
        ]);
    }

    /**
     * Tampilkan detail kamar
     */
    public function detail($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id);

        if (!$room) return;

        $this->view->setLayout('main')->render('rooms/detail', [
            'title' => 'Kamar ' . $room->room_number . ' - ' . APP_NAME,
            'room' => $room,
            'similarRooms' => $this->getSimilarRooms($roomModel, $room)
        ]);
    }

    /**
     * Search kamar berdasarkan tanggal
     */
    public function search()
    {
        $params = $this->getFilterParams();

        if (!$this->validateSearchDates($params['check_in'], $params['check_out'], 'rooms')) {
            return;
        }

        $roomModel = $this->loadModel('Room');
        $allRooms = $this->isValidRoomType($params['type'])
            ? $roomModel->getByType($params['type'])
            : $roomModel->getAvailable();

        $availableRooms = $this->filterByDateAvailability(
            $roomModel, 
            $allRooms, 
            $params['check_in'], 
            $params['check_out']
        );

        $this->view->setLayout('main')->render('rooms/search', [
            'title' => 'Hasil Pencarian - ' . APP_NAME,
            'rooms' => $availableRooms,
            'checkIn' => $params['check_in'],
            'checkOut' => $params['check_out'],
            'selectedType' => $params['type']
        ]);
    }

    /**
     * Filter kamar by type (AJAX)
     */
    public function filterByType()
    {
        $type = $_GET['type'] ?? '';
        $roomModel = $this->loadModel('Room');

        $rooms = $this->isValidRoomType($type)
            ? $roomModel->getByType($type)
            : $roomModel->getAvailable();

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
        $room = $this->findRoomOrJsonFail($roomModel, $id);

        if (!$room) return;

        $this->json([
            'success' => true,
            'room' => $this->formatRoomForJson($room)
        ]);
    }

    /**
     * Check availability (AJAX)
     */
    public function checkAvailability($id)
    {
        $params = $this->getFilterParams();

        if (!$this->validateDatesOrJsonFail($params['check_in'], $params['check_out'])) {
            return;
        }

        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrJsonFail($roomModel, $id);

        if (!$room) return;

        $isAvailable = $roomModel->isAvailableForDates($id, $params['check_in'], $params['check_out']);
        $nights = $this->loadModel('Booking')->calculateNights($params['check_in'], $params['check_out']);

        $this->json($this->buildRoomAvailabilityResponse($room, $isAvailable, $nights));
    }

    /**
     * Get all room types
     */
    public function types()
    {
        $roomModel = $this->loadModel('Room');

        $this->view->setLayout('main')->render('rooms/types', [
            'title' => 'Tipe Kamar - ' . APP_NAME,
            'types' => $this->getRoomTypesData($roomModel)
        ]);
    }
}