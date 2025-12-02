<?php

namespace Core;

use Core\Traits\HasConnection;
use Core\Traits\HasStatement;
use Core\Traits\HasFetch;
use Core\Traits\HasTransaction;

class Database
{
    use HasConnection, HasStatement, HasFetch, HasTransaction;

    private string $host;
    private string $user;
    private string $pass;
    private string $db_name;

    private static ?Database $instance = null;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->db_name = DB_NAME;

        $this->connect();
    }

    /**
     * Singleton pattern - one connection for all
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}