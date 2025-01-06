<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use App\Entities\Trajet;
use App\Services\Query;

class TrajetModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getTrajetData(int $trajet_id) {
        $trajet_data = (new Query($this->pdo))
        ->select()
        ->from('trips')
        ->where('id','=',(string)$trajet_id)
        ->get();
        return new Trajet($trajet_data);
    }

    public function deleteTrajet($id) {
        $query = (new Query($this->pdo))
              ->delete()
              ->from('trips')
              ->where('id', '=',$id)
              ->get();
    }
}