<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class Model {
    protected \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    
}