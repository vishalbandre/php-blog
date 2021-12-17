<?php

namespace Carousel\Category;

// Using Database namespace
use Database;


/**
 * Class to set common methods to interact with categories table.
 */

class Category
{
    public function __construct()
    {
    }

    /**
     * Return all the recent categories.
     */
    public static function get_all_categories()
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('carousels_categories');
    }

    /**
     * Return the category with specified $id.
     */
    public static function get_category($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('carousels_categories', $id);
    }

    /**
     * Returns the count of categories
     */
    public static function count()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the categories table
        return $c->getCount('carousels_categories');
    }

    /**
     * Create new record
     */
    public static function insert_category($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('carousels_categories', $data_array);
    }

    /**
     * Update existing record
     */
    public static function update_category($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->update('carousels_categories', $data_array, $id);
    }

    /**
     * Delete a record from table
     */
    public static function delete_category($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('carousels_categories', $id);
    }

    /**
     * Get id by category name
     */
    public function getCategoryIdByName($category_name)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return the id of the category with specified name
        return $c->getIdByAttribute('carousels_categories', 'name', $category_name);
    }
}
