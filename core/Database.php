<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private $host;
    private $user;
    private $pass;
    private $db_name;

    private $dbh;    // Database handler
    private $stmt;   // Statement

    private static $instance = null;

    public function __construct() {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->db_name = DB_NAME;

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    // Singleton pattern - satu koneksi untuk semua
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
        return $this;
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value): $type = PDO::PARAM_INT; break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default: $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Tambahan: Get last insert ID
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    // Tambahan: Begin transaction
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    // Tambahan: Commit transaction
    public function commit() {
        return $this->dbh->commit();
    }

    // Tambahan: Rollback transaction
    public function rollback() {
        return $this->dbh->rollBack();
    }
}