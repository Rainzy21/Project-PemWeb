<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class Room extends Model
{
    protected static $table = "rooms";

    #ambil semua kamar
    public static function all()
    {
        $db = Database::getInstance();
        $query = $db->query("SELECT * FROM " . self::$table . " ORDER BY id DESC");

        return $query->fetchALL();
    }


    #ambil kamar berdasarkan ID
    public static function find($id)
    {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM " . self::$table . " WHERE id = ?");
        $query->execute([$id]);

        return $query->fetch();
    }

    #tambah kamar
    public static function create($data)
    {
        $db = Database::getInstance();
        $query = $db->prepare("
            INSERT INTO " . self::$table . " (room_name, description, price, status)
            VALUES (?, ?, ?, ?)
        ");

        return $query->execute([
            $data['room_name'],
            $data['description'],
            $data['price'],
            $data['status']
        ]);
    }

    #update kamar
    public static function updateData($id, $data)
    {
        $db = Database::getInstance();

        $query = $db->prepare("
            UPDATE " . self::$table . "
            SET room_name = ?, description = ?, price = ?, status = ?
            WHERE id = ?
        ");

        return $query->execute([
            $data['room_name'],
            $data['description'],
            $data['price'],
            $data['status'],
            $id
        ]);
    }


    #hapus kamar
    public static function delete($id)
    {
        $db = Database::getInstance();

        $query = $db->prepare("DELETE FROM " . self::$table . " WHERE id = ?");
        return $query->execute([$id]);
    }
}


