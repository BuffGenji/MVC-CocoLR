<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use App\Services\Query;

class SignUpModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }


    /**
     * The signUp method inserts a new user into the database
     * it will need access to thr form_values from the getParsedBody functions
     * @see https://www.slimframework.com/docs/v3/objects/request.html#the-request-body 
     * 
     * 
     * signing up needs to contain ALL of the values found in the database that fill a person's table row
     * @see app/Config/current.sql
     * @param array $form_values
     */
    public function signUp(array $form_values)
    {
        $user_signup = (new Query($this->pdo))
            ->insertInto('persons', ['name', 'email', 'password'])
            ->values([$form_values['name'],
                      $form_values['email'],
                      $form_values['password']])
            ->get();
    }

    public function checkIfInDb($email)
    {
        $user = (new Query($this->pdo))
            ->select(['email'])
            ->from('persons')
            ->where('email', '=', $email)
            ->get();
        return $user;
    }
}
