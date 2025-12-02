<?php

namespace App\Controllers\Traits;

trait FormatsRoomData
{
    /**
     * Format price to Rupiah
     */
    protected function formatPrice(float $price): string
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Format room data for JSON response
     */
    protected function formatRoomForJson(object $room): array
    {
        return [
            'id' => $room->id,
            'room_number' => $room->room_number,
            'room_type' => $room->room_type,
            'price_per_night' => $room->price_per_night,
            'price_formatted' => $this->formatPrice($room->price_per_night),
            'description' => $room->description,
            'image' => $room->image,
            'is_available' => $room->is_available
        ];
    }

    /**
     * Build availability response
     */
    protected function buildRoomAvailabilityResponse(object $room, bool $isAvailable, int $nights): array
    {
        $totalPrice = $nights * $room->price_per_night;

        return [
            'success' => true,
            'available' => $isAvailable,
            'room_number' => $room->room_number,
            'nights' => $nights,
            'price_per_night' => $room->price_per_night,
            'total_price' => $totalPrice,
            'price_formatted' => $this->formatPrice($room->price_per_night),
            'total_formatted' => $this->formatPrice($totalPrice)
        ];
    }

    /**
     * Get room types with descriptions
     */
    protected function getRoomTypesData($roomModel): array
    {
        return [
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
    }
}