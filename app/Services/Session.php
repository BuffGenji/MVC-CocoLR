<?php

declare(strict_types=1);

namespace App\Services;

session_start();


/**
 * This class handles everything to do with the session so that we don't have to scatter session_start()
 * all over the place
 */
class Session
{


    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key];
    }

    public static function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function fill(string $email) {
        
    }
}
