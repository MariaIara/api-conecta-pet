<?php

namespace App\Core\config;

use PDO;
use PDOException;

abstract class Database
{
    private static $conn;
    protected $config;
    protected $db;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/config.php';
        $this->db = $this->getConn($this->config['database']);
    }

    protected static function getConn(array $config, string $username = null, string $password = null): PDO
    {
        if (!isset(self::$conn)) {
            $dsn = 'mysql:' . http_build_query($config, '', ';');

            $username = $username ?? $config['username'] ?? 'root';
            $password = $password ?? $config['password'] ?? '';

            try {
                self::$conn = new PDO($dsn, $username, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
