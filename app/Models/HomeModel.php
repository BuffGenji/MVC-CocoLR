<?php
declare(strict_types=1);
namespace App\Models;

use App\Config\Database;
use App\Entities\Trajet;

class HomeModel
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }


    public function getAllTrajets()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM trips');
        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $trajet_objects = array_map(fn($trajet) => new Trajet($trajet), $trajets);
        return $trajet_objects;
    }

    public function getTrajetById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM trips WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();  
    }
}