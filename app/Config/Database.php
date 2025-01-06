<?php
declare(strict_types=1);    
namespace App\Config;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $dsn = 'pgsql:host=localhost;dbname=Slimmy;';
        $username = 'postgres';
        $password = 'q';

        try {
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION); 
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}