<?php

namespace App\Controllers\Traits;

trait HandlesRoomFilter
{
    protected array $validRoomTypes = ['standard', 'deluxe', 'suite'];

    /**
     * Get filter parameters from query string
     */
    protected function getFilterParams(): array
    {
        return [
            'type' => $_GET['type'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'check_in' => $_GET['check_in'] ?? '',
            'check_out' => $_GET['check_out'] ?? ''
        ];
    }

    /**
     * Check if room type is valid
     */
    protected function isValidRoomType(?string $type): bool
    {
        return $type && in_array($type, $this->validRoomTypes);
    }

    /**
     * Get rooms based on filter
     */
    protected function getFilteredRooms($roomModel, array $params): array
    {
        if ($this->isValidRoomType($params['type'])) {
            return $roomModel->getByType($params['type']);
        }

        if ($params['min_price'] && $params['max_price']) {
            return $roomModel->getByPriceRange($params['min_price'], $params['max_price']);
        }

        return $roomModel->getAvailable();
    }

    /**
     * Filter rooms by date availability
     */
    protected function filterByDateAvailability($roomModel, array $rooms, string $checkIn, string $checkOut): array
    {
        return array_filter($rooms, function ($room) use ($roomModel, $checkIn, $checkOut) {
            return $roomModel->isAvailableForDates($room->id, $checkIn, $checkOut);
        });
    }

    /**
     * Get similar rooms (same type, exclude current)
     */
    protected function getSimilarRooms($roomModel, object $room, int $limit = 3): array
    {
        $similar = $roomModel->getByType($room->room_type);
        $similar = array_filter($similar, fn($r) => $r->id != $room->id);
        
        return array_slice($similar, 0, $limit);
    }
}