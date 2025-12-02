<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class User extends Model
{
    protected static $table = "users";

    #ambil semua user
    public static function all()
    {
        $db = Database::getInstance();
        $query = $db->query("SELECT * FROM " . self::$table . " ORDER BY id DESC");

        return $query->fetchALL();
    }

    #ambil satu user berdasarkan ID
    public static function find($id)
    {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM " . self::$table . " WHERE id = ?");
        $query->execute([$id]);

        return $query->fetch();
    }

    #ambil user berdasarkan email
    public static function findByEmail($email)
    {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM " . self::$table . " WHERE email = ?");
        $query->execute([$email]);

        return $query->fetch();
    } 

    #tambah user
    public static function create($data)
    { 
        $db = Database::getInstance();

        $query = $db->prepare("
            INSERT INTO " . self::$table . " (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        return $query->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        ]);
    }

    #update user
    public static function updateData($id, $data)
    {
        $db = Database::getInstance();

        $query = $db->prepare("
            UPDATE " . self::$table . "
            SET name = ?, email = ?, password = ?, role = ?
            WHERE id = ?
        ");

        return $query->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $id
        ]);
    }

    #hapus user
    public static function delete($id)
    {
        $db = Database::getInstance();

        $query = $db->prepare("DELETE FROM " . self::$table . " WHERE id = ?");
        return $query->execute([$id]);
    }
}