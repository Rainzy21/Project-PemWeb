<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class Booking extends Model
{
    protected static $table = "bookings";

    #ambil semua booking dan join user beserta room
    public static function all()
    {
        $db = Database::getInstance();

        $sql = "
            SELECT b.*, u.name AS user_name, r.room_name
            FROM bookings b
            LEFT JOIN users u ON u.id = b.user_id
            LEFT JOIN rooms r ON r.id = b.room_id
            ORDER BY b.id DESC
        ";

        $query = $db->query($sql);
        return $query->fetchAll();
    }

    #ambil booking berdasarkan ID
    public static function find($id)
    {
        $db = Database::getInstance();

        $sql = "
            SELECT b.*, u.name AS user_name, r.room_name
            FROM bookings b
            LEFT JOIN users u ON u.id = b.user_id
            LEFT JOIN rooms r ON r.id = b.room_id
            WHERE b.id = ?
        ";

        $query = $db->prepare($sql);
        $query->execute([$id]);
        return $query->fetch();
    }

    #tambah booking
    public static function create($data)
    {
        $db = Database::getInstance();

        $query = $db->prepare("
            INSERT INTO " . self::$table . "
            (user_id, room_id, check_in, check_out, status, total_price)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $query->execute([
            $data['user_id'],
            $data['room_id'],
            $data['check_in'],
            $data['check_out'],
            $data['status'],
            $data['total_price']
        ]);        
    }

    #update booking
    public static function updateData($id, $data)
    {
        $db = Database::getInstance();

        $query = $db->prepare("
            UPDATE " . self::$table . "
            SET user_id = ?, room_id = ?, check_in = ?, check_out = ?, status = ?, total_price = ?
            WHERE id = ?
        ");

        return $query->execute([
            $data['user_id'],
            $data['room_id'],
            $data['check_in'],
            $data['check_out'],
            $data['status'],
            $data['total_price'],
            $id
        ]);
    }


    #hapus booking
    public static function delete($id)
    {
        $db = Database::getInstance();

        $query = $db->prepare("DELETE FROM " . self::$table . " WHERE id = ?");
        return $query->execute([$id]);
    }
}