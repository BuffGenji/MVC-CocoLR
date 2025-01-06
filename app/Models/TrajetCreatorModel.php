<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use App\Entities\Trajet;
use App\Services\Query;
use App\Services\Session;

class TrajetCreatorModel
{
    private  $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    /**
     * This funciton will fill a trip with data, most importanly the data coming straight form user input
     * destination, departure, date, time ... and the id of the person (person_id) 
     * in the dataabse will be added unde the hood
     */
    public function createTrajet(array $user_input) : Trajet
    {   $cleaned_input = $this->clean($user_input);
        $columns = array_keys($cleaned_input);
        array_unshift($columns, 'person_id');
        $trajet = (new Query($this->pdo))
        ->insertInto('trips',$columns)
        ->values([
            'person_id' => Session::get('id'),
            'start_date' => $cleaned_input['start_date'],
            'end_date' => $cleaned_input['end_date'],   
            'departure' => $cleaned_input['departure'],
            'destination' => $cleaned_input['destination']
        ])
        ->get();
        return new Trajet($cleaned_input);
    }

    /**
     * This function will clean the user input + NEEDED BECAUSE Mapbox changes the names of the tags
     * you use to get the data. This is primarily to RENAME the keys of the user input, and delete old ones
     * @param array $user_input
     * 
     * Although understabndable, not elegant, to refactor
     */
    public function clean(array $user_input) : array
    {
        $user_input['departure'] = htmlspecialchars($user_input['departure_address-search']);
        $user_input['destination'] = htmlspecialchars($user_input['destination_address-search']);

        unset($user_input['departure_address-search']);
        unset($user_input['destination_address-search']);

        return $user_input;
    }
}