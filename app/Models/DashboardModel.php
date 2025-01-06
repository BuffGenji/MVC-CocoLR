<?php
declare(strict_types=1);
namespace App\Models;
session_start();

use App\Config\Database;
use App\Entities\Trajet as EntitiesTrajet;
use App\Interfaces\Page;
use App\Services\Query;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Entities\Trajet;
use App\Services\Session;

class DashboardModel
{

    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
        // session_start();
    }


    /**
     * This will make the Trajet entities
     * @return Trajet[]
     */
    public function getuserTrajets() : array
     {
        // here we check if the user is logged in / authenticated
        // if not, we redirect to the login page
         
        /**TESTING , with NO AUTHENTICATION */

        // gets all trajets from database with the name of the user
        $trajets = (new Query($this->pdo))
            ->select()
            ->from('trips')
            ->where(['person_id'],'=', [Session::get('id')]) // works wih the user 
            ->get();
        // creates an array of Trajet objects
        $arr_trajets_objects = array_map(function($trajet) {
           return new Trajet($trajet);
        }, $trajets);
        
        return $arr_trajets_objects;
    }
}