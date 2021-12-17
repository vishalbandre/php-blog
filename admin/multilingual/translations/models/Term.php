<?php

namespace Admin;

// Using Database namespace
use Database;

/**
 * Class to set common methods to interact with terms table.
 */

class Term
{
    private $id;
    private $term;

    public function __construct($term = null)
    {
        $this->term = $term;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Return all terms.
     */
    public function getAll()
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('terms');
    }

    /**
     * Return the term with specified $id.
     */
    public function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('terms', $id);
    }

    /**
     * Get term by term name
     */
    public function getByTerm($term)
    {
        // Get a database connection
        $c = new Database\Connection();
    
        return $c->getByAttribute('terms', 'term', $term);
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('terms', $data_array);
    }

    /**
     * Update existing record
     */
    public function update($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->updateByAttribute('terms', $data_array, 'id', $id);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('terms', $id);
    }

    /**
     * Returns the count of languages
     */
    public function count()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the translations table
        return $c->getCount('terms');
    }
}
