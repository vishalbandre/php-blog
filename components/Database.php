<?php

namespace Database;

// Using mysqli namespace
use mysqli;

use function PHPSTORM_META\type;

/**
 * Connection class provides:
 * 1. Database connection
 * 2. Generic CRUD (Create, Read, Update, Delete) & Count Operations
 */
class Connection
{
    private $server;
    private $dbuser;
    private $dbpass;
    private $db;
    private $conn;

    /**
     * Initialize credentials with default values
     */
    public function __construct()
    {
        $this->server = 'localhost';
        $this->dbuser = 'php_blog_user';
        $this->dbpass = 'php_blog_password';
        $this->db = 'php_blog';
    }

    /**
     * Make and return a connection
     */
    public function connect()
    {
        $conn = new mysqli($this->server, $this->dbuser, $this->dbpass, $this->db);
        return $conn;
    }

    /**
     * Generic query
     */
    public function query($sql)
    {
        // Get a connection
        $conn = $this->connect();

        // Return a query
        return $conn->query($sql);
    }

    /**
     * Insert record
     */
    public function insert($table, $data)
    {
        // Get record as an array
        $columns = implode(", ", array_keys($data));

        // Get values separated
        $values  = implode(", ", array_map(function ($val) {
            if (!is_numeric($val))
                $val = sprintf("'%s'", $val);
            else
                return $val;
            return $val;
        }, $data));

        // Insert values according to their columns in table
        $sql = "INSERT INTO $table ($columns) VALUES ($values);";

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            $id = mysqli_insert_id($conn);

            echo $id . '<br />';

            // Return id, if query is successful
            return $id;
        } else {
            print($conn->error);
        }

        // Return null, if operation failed
        return null;
    }

    /**
     * Update record
     */
    public function update($table, $data, $id)
    {

        // Provide data array in the form of key as field name
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value = "'$value'";
                $updates[] = "$key = $value";
            }
        }

        $values = implode(", ", $updates);

        $sql = "UPDATE $table SET $values WHERE id=$id";

        $conn = $this->connect();
        if ($conn->query($sql) === TRUE) {
            // If record updates successfully, return $id
            return $id;
        }

        // Will return null, if the operation fails
        return null;
    }

    /**
     * Update record
     */
    public function updateByAttribute($table, $data, $attribute, $_value)
    {

        // Provide data array in the form of key as field name
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value = "'$value'";
                $updates[] = "$key = $value";
            }
        }

        if (is_string($_value)) {
            $_value = "'$_value'";
        }

        $values = implode(", ", $updates);

        $sql = "UPDATE $table SET $values WHERE $attribute=$_value";

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            // If record updates successfully, return $value
            return $_value;
        }

        // Will return null, if the operation fails
        return null;
    }

    /**
     * Return all records from specified table.
     */
    public function getAllRecords($table = null)
    {
        $conn = $this->connect();

        $sql = "SELECT * FROM $table";

        // Execute the query
        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    /**
     * Return all records from specified table, based on supplied parameters
     */
    public function getAll($table = null, $offset = null, $per_page = null)
    {
        $conn = $this->connect();

        if ($table == 'posts') {

            if ($offset !== null && $per_page !== null) {
                $sql = "SELECT * FROM $table ORDER BY created_at DESC LIMIT $offset, $per_page";
            } else {
                $sql = "SELECT * FROM $table";
            }
        } else {
            $sql = "SELECT * FROM $table";
        }

        // Execute the query
        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    /**
     * Return all records from specified table, based on request attribute
     */
    public function getAllByAttribute($table = null, $attribute = null, $value = null)
    {
        $conn = $this->connect();

        $sql = "SELECT * FROM $table WHERE $attribute='$value'";

        // Execute the query
        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    /**
     * Return recent posts based on limit.
     */
    public function getAllByLimit($table, $limit)
    {
        $conn = $this->connect();

        $sql = "SELECT * FROM $table LIMIT $limit";

        // Execute the query
        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    // Get particular record from table
    public function get($table, $id)
    {

        // Select record having this id
        $sql = "SELECT * FROM $table WHERE id='$id'";

        $conn = $this->connect();

        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    // Get particular record from table
    public function getByAttribute($table, $attribute, $value)
    {

        // Return record as per specified attribute and value
        $sql = "SELECT * FROM $table WHERE $attribute='$value'";

        $conn = $this->connect();

        $result = $conn->query($sql);

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows <= 0) {
            $result = null;
        }

        // Return $result - it will either have results or null
        return $result;
    }

    // Future
    // getByAttributes($multiple_attributes)

    // Get id of a particular record from table based on the specified attribute
    public function getIdByAttribute($table, $attribute, $value)
    {

        // Return record as per specified attribute and value
        $sql = "SELECT id FROM $table WHERE $attribute='$value'";

        $conn = $this->connect();

        $result = $conn->query($sql);

        $result = $result->fetch_assoc();

        if ($result) {
            // Return id - it will either have id or null
            return $result['id'];
        } else {
            return null;
        }
    }

    /**
     * Get last inserted record
     */
    public function getRecent($table)
    {
        $sql = "SELECT * FROM $table LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $result = null;
        }
        return $result;
    }

    /**
     * Delete record from a table
     */
    public function delete($table, $id)
    {
        // Delete record having specific id
        $sql = "DELETE FROM $table WHERE id='$id'";

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            return true;
        }

        print($conn->error);

        // Return false, if operation failed
        return false;
    }

    // Delete particular record from table based on attribute and value provided
    public function deleteByAttribute($table, $attribute, $value)
    {
        // Delete record having specific attribute and corresponding value
        $sql = "DELETE FROM $table WHERE $attribute='$value'";

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            return true;
        }

        // Return false, if operation failed
        return false;
    }

    /**
     * Get the count of number of records present in table
     */
    public function getCount($table)
    {
        $sql = "SELECT COUNT(*) FROM $table";

        // Get the database connection
        $conn = $this->connect();

        $result = $conn->query($sql);

        // If there are no records set value of $result to zero
        if ($result->num_rows <= 0) {
            $result = 0;
        }

        // Will return records if there are any else zero
        return $result;
    }
}
