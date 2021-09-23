<?php

namespace Carousel\Image;

// Using Database namespace
use Database;


/**
 * Class to set common methods to interact with categories table.
 */

class CarouselImage
{
    public function __construct()
    {
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('carousels_images', $data_array);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('carousels_images', $id);
    }
}
