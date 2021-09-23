<?php

namespace User\Auth;

// Using Database namespace
use Database;


/**
 * Provide authentication methods
 */

class Auth
{
    /**
     * User Can Login with Username & Password Only
     */
    public static function login($username, $password)
    {
        // Get a database connection
        $c = new Database\Connection();
        $conn = $c->connect();

        $check = "SELECT * FROM users WHERE username='" . $username . "' and password='" . $password . "' LIMIT 1";
        $result = $conn->query($check);

        if ($result->num_rows <= 0) {
            $result = null;
        }

        return $result;
    }

    /**
     * Check for Login with Username & Password Only
     */
    public static function check_login($username, $password)
    {
        // Get a database connection
        $c = new Database\Connection();
        $conn = $c->connect();

        $check = "SELECT username, role FROM users WHERE username='" . $username . "' and password='" . $password . "' LIMIT 1";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }
}
