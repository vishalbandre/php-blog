<?php

namespace User;

// Using Database namespace
use Database;


/**
 * Class to set common methods to interact with users table.
 */

class User
{
    /**
     * Return all the users.
     */
    public static function getAll($offset = null, $per_page = null, $recent=true)
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('users', $offset, $per_page);
    }

    /**
     * Return ta user with the specified $id.
     */
    public static function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('users', $id);
    }

    /**
     * Returns the count of users
     */
    public static function count()
    {

        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the users table
        return $c->getCount('users');
    }

    /**
     * Create new record
     */
    public static function insert($data_array) {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('users', $data_array);
    }

    /**
     * Update existing record
     */
    public static function updateByUsername($data_array, $username) {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->update('users', $data_array, 'username', $username);
    }
}
