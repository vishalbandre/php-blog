<?php

namespace Admin;

// Using Database namespace
use Database;

use Admin\Term;

/**
 * Class to set common methods to interact with translations table.
 */

class Translation
{
    private $id;
    private $term;
    private $translation;
    private $language_id;

    public function __construct($term = null, $translation = null, $language_id = null)
    {
        $this->term = $term;
        $this->translation = $translation;
        $this->language_id = $language_id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Return a translation based on language and term
     */
    public function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('translations', 'id', $id);
    }

    /**
     * Return all translations.
     */
    public function getAll()
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('translations');
    }

    /**
     * Get translations by term id
     */
    public function getTranslationsByTermId($term_id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('translations', 'term_id', $term_id);
    }

    /**
     * Get translations by term 
     */
    public function getTranslationsByTerm($term)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        $t = new Term();
        $term = $t->getByTerm($term);

        $r = $term->fetch_assoc();

        $term_id = $r['id'];

        return $this->getTranslationsByTermId($term_id);
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('translations', $data_array);
    }

    /**
     * Update existing record
     */
    public function update($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->updateByAttribute('translations', $data_array, 'id', $id);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('translations', $id);
    }

    /**
     * Delete a translation by term id
     */
    public function deleteByTermId($term_id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->deleteByAttribute('translations', 'term_id', $term_id);
    }

    /**
     * Returns the count of languages
     */
    public function count()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the translations table
        return $c->getCount('translations');
    }

    /**
     * Translation API
     */
    public static function translate($term, $lang, $get = null)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Get lang id by language prefix
        $language = new Language();
        $language_id = $language->getIdByPrefix($lang);

        $term = trim($term);

        // Get term id by term
        $t = new Term();
        $term_ = $t->getByTerm($term);

        if ($term_) {
            $r = $term_->fetch_assoc();
            $term_id = $r['id'];
        } else {
            $term_id = null;
        }

        if ($term_id) {
            // Get translation by term id and language id
            $conn = $c->connect();

            $sql = "SELECT * FROM translations WHERE term_id = '$term_id' AND language_id = '$language_id'";

            // Execute the query
            $result = $conn->query($sql);

            // If there is a result
            if ($result->num_rows > 0) {
                // Get the first row
                $row = $result->fetch_assoc();

                $v = (string)$row['translation'];

                // Print the translation
                if (empty($v)) {
                    // Print the term if translation is not available.
                    if ($get)
                        return $term;
                    else
                        echo $term;
                } else {
                    // Print the translation if available.
                    if ($get)
                        return $v;
                    else
                        echo $v;
                }
            } else {
                // Echo the term if `translation` is not available.
                if ($get)
                    return $term;
                else
                    echo $term;
            }
        } else {
            // Fallback to default argument if nothing returns transation
            if ($get)
                return $term;
            else
                echo $term;
        }
    }
}
