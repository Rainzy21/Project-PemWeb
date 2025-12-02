<?php

namespace Core\Traits;

use PDO;
use PDOException;

trait HasConnection
{
    private ?PDO $dbh = null;

    /**
     * Create database connection
     */
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }

    /**
     * Handle connection error
     */
    protected function handleConnectionError(PDOException $e): void
    {
        if (defined('APP_DEBUG') && APP_DEBUG) {
            die('Connection failed: ' . $e->getMessage());
        }
        
        die('Database connection error. Please try again later.');
    }

    /**
     * Get PDO instance
     */
    public function getPdo(): ?PDO
    {
        return $this->dbh;
    }

    /**
     * Check if connected
     */
    public function isConnected(): bool
    {
        return $this->dbh !== null;
    }
}