<?php

namespace Admin;

// Using Database namespace
use Database;

/**
 * Class to set common methods to interact with languages table.
 */

class Language
{
    private $id;
    private $name;
    private $prefix;

    public function __construct($name = null, $prefix = null)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set language name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return a language name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set language prefix, e.g., mr, hi, fr, cn
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Return a language prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Return a language based on language name
     */
    public function getByLanguageName($name)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('languages', 'name', $name);
    }

    /**
     * Return a language based on language prefix
     */
    public function get($prefix)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('languages', 'prefix', $prefix);
    }

    /**
     * Get default language
     */
    public function getDefault()
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        $sql = "SELECT * FROM languages WHERE is_default=1";

        // Execute the query
        $result = $conn->query($sql);

        return $result;
    }

    /**
     * Return all languages.
     */
    public function getAllLanguages()
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('languages');
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('languages', $data_array);
    }

    /**
     * Update existing record
     */
    public function update($data_array, $prefix)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->updateByAttribute('languages', $data_array, 'prefix', $prefix);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('languages', $id);
    }

    /**
     * Get id of a requested language by prefix
     */
    public function getIdByPrefix($prefix)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns id of a requested language, else null
        $c->getIdByAttribute('languages', 'prefix', $prefix);

        return $c->getIdByAttribute('languages', 'prefix', $prefix);
    }

    /**
     * Get requested language by id
     */
    public function getById($id)
    {
        // Get a database connection
        $c = new Database\Connection();
        // echo 'id';
    
        $results = $c->get('languages', $id);

        return $results;

    }

    /**
     * Get prefix of a requested language by id
     */
    public function getPrefixById($id)
    {
        // Get a database connection
        $c = new Database\Connection();
        $conn = $c->connect();

        // Return record as per specified attribute and value
        $sql = "select prefix from languages where id=$id";

        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if (!$result) {
            return null;
        }

        $r = $result->fetch_assoc();

        // Return $result - it will either have results or null
        return $r['prefix'];
    }

    /**
     * Returns the count of languages
     */
    public function count()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the languages table
        return $c->getCount('languages');
    }
}
