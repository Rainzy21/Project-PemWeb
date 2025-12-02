<?php

namespace App\Controllers\Traits;

trait ValidatesRoomData
{
    protected array $validRoomTypes = ['standard', 'deluxe', 'suite'];

    protected array $roomValidationRules = [
        'room_number' => 'required',
        'room_type' => 'required',
        'price_per_night' => 'required'
    ];

    /**
     * Get room data from POST
     */
    protected function getRoomInputData(): array
    {
        return [
            'room_number' => trim($_POST['room_number'] ?? ''),
            'room_type' => $_POST['room_type'] ?? 'standard',
            'price_per_night' => floatval($_POST['price_per_night'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];
    }

    /**
     * Validate room data
     */
    protected function validateRoomData(array $data, string $redirectTo): bool
    {
        $errors = $this->validate($data, $this->roomValidationRules);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $_SESSION['old'] = $data;
            $this->redirect($redirectTo);
            return false;
        }

        if (!$this->isValidRoomType($data['room_type'])) {
            $this->setFlash('error', 'Tipe kamar tidak valid');
            $this->redirect($redirectTo);
            return false;
        }

        if ($data['price_per_night'] <= 0) {
            $this->setFlash('error', 'Harga harus lebih dari 0');
            $this->redirect($redirectTo);
            return false;
        }

        return true;
    }

    /**
     * Check if room type is valid
     */
    protected function isValidRoomType(?string $type): bool
    {
        return $type && in_array($type, $this->validRoomTypes);
    }

    /**
     * Check room number uniqueness
     */
    protected function isRoomNumberUnique($roomModel, string $roomNumber, ?int $excludeId = null): bool
    {
        $existingRoom = $roomModel->findByRoomNumber($roomNumber);

        if (!$existingRoom) {
            return true;
        }

        return $excludeId && $existingRoom->id == $excludeId;
    }
}