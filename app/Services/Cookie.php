<?php
declare(strict_types=1);
namespace App\Services; 


/**
 * This is to make it easier to work with cookies, we will keep certain "profiles" of cookies,
 * and the identifier for that type of cookie. To see what is inside
 * 
 */

class Cookie {

    public function setCookieID($cookieID) {
        setcookie('cookie_ID', $cookieID, time() + 3600, '/');
    }

    public function getCookieID() {
        return $_COOKIE['cookie_ID'];
    }

}
