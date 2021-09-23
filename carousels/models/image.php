<?php

namespace Carousel;

// Using Database namespace
use Database;


/**
 * Class to set common methods to interact with images table.
 */

class Image
{
    public function __construct()
    {
    }

    /**
     * Return all images by upload date.
     */
    public function getAll($offset = null, $per_page = null)
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('images', $offset, $per_page);
    }

    /**
     * Return the category with specified $id.
     */
    public function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('images', $id);
    }

    /**
     * Returns the count of categories
     */
    public function count()
    {

        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the images table
        return $c->getCount('images');
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('images', $data_array);
    }

    /**
     * Update existing record
     */
    public function update($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->update('images', $data_array, $id);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('images', $id);
    }
}
