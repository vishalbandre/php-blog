<?php

namespace Post;

// Using Database namespace
use Database;

/**
 * Class to set common methods to interact with posts table.
 */

class Post
{
    private $id;
    private $user;
    private $title;
    private $description;
    private $body;
    private $created_at;
    private $updated_at;

    public function __construct($user = null, $title = null, $description = null, $body = null)
    {
        $this->user = $user;
        $this->title = $title;
        $this->description = $description;
        $this->body = $body;
    }

    public function getId()
    {
        return $this->id;
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

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
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

        return $c->getAll('posts', $offset, $per_page);
    }

    /**
     * Return recent posts based on limit.
     */
    public function getAllByLimit($limit)
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAll('posts', $limit);
    }

    /**
     * Return the post with specified $id.
     */
    public function get($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->get('posts', $id);
    }

    /**
     * Return the post with specified slug.
     */
    public function getBySlug($slug)
    {
        // Get a database connection
        $c = new Database\Connection();

        return $c->getByAttribute('posts', 'slug', $slug);
    }

    /**
     * Returns the count of posts
     */
    public function count()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return how many items are there in the posts table
        return $c->getCount('posts');
    }

    /**
     * Create new record
     */
    public function insert($data_array)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert('posts', $data_array);
    }

    /**
     * Update existing record
     */
    public function update_post($data_array, $id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->update('posts', $data_array, $id);
    }

    /**
     * Delete a record from table
     */
    public function delete_post($id)
    {
        // Get a database connection
        $c = new Database\Connection();

        // Returns true if record is deleted, else false
        return $c->delete('posts', $id);
    }
}
