<?php
declare(strict_types=1);
namespace App\Models;

use App\Config\Database;
use App\Services\Query;

class ConfirmTrajetModel
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

     /**
     * This funciton will fill a trip with data, most importanly the data coming straight form user input
     * destination, departure, date, time ... and the id of the person (person_id) 
     * in the dataabse will be added unde the hood
     */
    // public function createTrajet(array $user_input)
    // {
    //  // since all of the fields are required we will assume all fo the NOT NULL fields are filled
    //  // user innput will have
    //  // destination , start date and end date

    //  // from out side we will set the perosn id and the database will take care of the id
    //  $trajet = (new Query($this->pdo))
    //     ->insertInto('trips',array_keys($user_input))
    //     ->values([  
    //         'person_id' => $_SESSION['id'],
    //         'destination' => $user_input['destination'],
    //         'departure' => $user_input['departure'],
    //         'start_date' => $user_input['start_date'],
    //         'end_date' => $user_input['end_date']
    //     ])
    //     ->get();
    //         return $message = (!$trajet ?
    //         'Trajet could not be created' :
    //         'Trajet created successfully');
    // }
}