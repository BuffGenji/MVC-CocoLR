<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use App\Services\Query;
use App\Services\Session;

class LogInModel
{

    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function checkIfInDb(string $email, string $password)
    {
        $user = (new Query($this->pdo))
            ->select()
            ->from('persons')
            ->where(
                ['email', 'password'],
                '=',
                [$email, $password]
            )
            ->get();
        return $user;
    }


    /**
     * This isn't where I would like to place this function, it is a bootleg
     * version of the project still.
     * 
     * This funciton fills the Session with user values that can be accessed from anywhere in the app
     * and is useful for gaps in information or as a guide for SQL queries
     */
    public function fillSession(string $email): void
    {
        $query = (new Query($this->pdo))
            ->select(['name', 'email','id'])
            ->from('persons')
            ->where('email', '=', $email)
            ->get();
        if (!empty($query)) { 
            Session::set('name', $query[0]['name']);
            Session::set('email', $query[0]['email']);
            Session::set('id', $query[0]['id']);
        }
    }

    public function cleanInputs(array $form_values) {
        foreach($form_values as $name => $value) {
            $value = htmlspecialchars($value);
        }
        return $form_values;
    }
}
