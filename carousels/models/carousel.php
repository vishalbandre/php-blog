<?php

namespace Carousel;

// Using Database namespace
use Database;


/**
 * Class to set common methods to interact with carousels table.
 */

class Carousel
{
    private $id;
    private $category_id;
    private $user;
    private $title;
    private $description;
    private $created_at;
    private $updated_at;

    public function __construct($category_id=null, $user = null, $title = null, $description = null)
    {
        $this->category_id = $category_id;
        $this->user = $user;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setTitle($title)
    {
        return $this->title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Return all the recent posts.
     */
    public function getAll($offset = null, $per_page = null)
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('carousels', $offset, $per_page);
    }

    /**
     * Return the carousel with specified $id.
     */
    public function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('carousels', $id);
    }

    /**
     * Return the carousel with specified $attribute & $value.
     */
    public function getByAttribute($attribute, $value)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('carousels', $attribute, $value);
    }

    /**
     * Return images corresponding to the particular carousel.
     */
    public function getGallery($caraousel_id)
    {
        // Get a database connection.
        $c = new Database\Connection();

        // Left Outer Join operation to get carousel images
        $sql = "SELECT * FROM images LEFT OUTER JOIN carousels_images ON images.id = carousels_images.image_id AND carousels_images.carousel_id = $caraousel_id LEFT OUTER JOIN carousels ON carousels_images.carousel_id = carousels.id where carousels.id IS NOT NULL";

        $result = $c->query($sql);

        return $result;
    }



    /**
     * Returns the count of carousels
     */
    public function count()
    {

        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the carousels table
        return $c->getCount('carousels');
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('carousels', $data_array);
    }

    /**
     * Update existing record
     */
    public function update($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->update('carousels', $data_array, $id);
    }

    /**
     * Delete a record from table
     */
    public function delete($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('carousels', $id);
    }
}
