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

    public function getDescription()
    {
        return $this->description;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
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
     * Return all the posts by user.
     */
    public function getAllByUser($user)
    {
        // Get a database connection.
        $c = new Database\Connection();

        return $c->getAllByAttribute('posts', 'user', $user);
    }

    /**
     * Return all base posts by user.
     */
    public function getAllBasePostsByUser($user, $lang_id)
    {
        // Get a database connection.
        $c = new Database\Connection();
        
        $conn = $c->connect();
        
        $sql = "SELECT * FROM posts WHERE user='$user' AND language_id=$lang_id ORDER BY updated_at DESC";

        // Execute the query
        $result = $conn->query($sql);

        if($result)
            return $result;
        else
            return false;
    }

    /**
     * Return all base posts by user.
     */
    public function getAllBasePosts()
    {
        // Get a database connection.
        $c = new Database\Connection();
        
        $conn = $c->connect();
        
        $sql = "SELECT * FROM posts WHERE base_post_id IS NULL";

        // Execute the query
        $result = $conn->query($sql);

        if($result)
            return $result;
        else
            return false;
    }

    /**
     * Return all the recent posts by a specified language.
     */
    public function getAllPostsByLanguage($offset = null, $per_page = null, $lang_id = null)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        if ($offset !== null && $per_page !== null && $lang_id !== null) {
            $l = (int)$lang_id;
            $sql = "SELECT * FROM posts WHERE language_id=$l ORDER BY updated_at DESC LIMIT $offset, $per_page";
        } else {
            $l = (int)$lang_id;
            $sql = "SELECT * FROM posts WHERE language_id=$l ORDER BY updated_at DESC";
        }

        // Execute the query
        $result = $conn->query($sql);

        if($result)
            return $result;
        else
            return false;
    }

    /**
     * Return all translations that belong to a specified base post.
     */
    public function getAllLanguageVariants($base_post_id)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        if ($base_post_id !== null)
            $sql = "SELECT * FROM posts WHERE base_post_id=$base_post_id";
        else
            return null;

        // Execute the query
        $result = $conn->query($sql);

        return $result;
    }

    /**
     * Return all translations that belong to a specified base post.
     */
    public function getAllLanguageSiblingPosts($id)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        if ($id !== null) {
            $sql = "SELECT base_post_id FROM posts WHERE id=$id";
            // Execute the query
            $result = $conn->query($sql);
            $results = $result->fetch_assoc();
            $base_post_id = $results['base_post_id'];
            return $this->getAllLanguageVariants($base_post_id);
        } else
            return null;
    }

    /**
     * Return all translations that belong to a specified base post.
     */
    public function getBasePost($id)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        if ($id !== null) {
            $sql = "SELECT base_post_id FROM posts WHERE id=$id";
            // Execute the query
            $result = $conn->query($sql);
            $results = $result->fetch_assoc();
            $base_post_id = $results['base_post_id'];
            $sql = "SELECT * FROM posts WHERE id=$base_post_id";
            return $result = $conn->query($sql);
        } else
            return null;
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

        $r = $result->fetch_assoc();

        // If there are no results present, set the value of $result to null.
        if ($result->num_rows == 0) {
            return null;
        }

        // Return $result - it will either have results or null
        return $r['prefix'];
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
    public function count($lang_id=null)
    {
        // Get a database connection
        $c = new Database\Connection();

        if ($lang_id !== null) {
            $sql = "SELECT COUNT(*) FROM posts WHERE language_id=$lang_id";
        } else {
            $sql = "SELECT COUNT(*) FROM posts";
        }

        $conn = $c->connect();

        $result = $conn->query($sql);

        $r = $result->fetch_assoc();

        // return posts count
        return $r['COUNT(*)'];
    }

    /**
     * Returns the count of posts
     */
    public function countBasePosts()
    {
        // Get a database connection
        $c = new Database\Connection();

        // Get a database connection.
        $conn = $c->connect();

        $sql = "SELECT COUNT(*) FROM posts WHERE language_id=1";

        // Execute the query
        $result = $conn->query($sql);

        if($result)
            return $result;
        else
            return false;
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

    /**
     * MULTILINGUAL SEARCHING
     */
    /**
     * Return all translations that belong to a specified base post.
     */
    public function search($term, $lang_id)
    {
        // Get a database connection.
        $c = new Database\Connection();
        $conn = $c->connect();

        if ($term !== null) {
            // Search for the term in the title, description and body fields
            $sql = "SELECT * FROM posts WHERE language_id=$lang_id AND (title LIKE '%$term%' OR description LIKE '%$term%' OR body LIKE '%$term%')";
        } else {
            // if there is no term then return null
            $sql = null;
        }

        // Execute the query, return the results, or null if no results
        if ($sql) {
            // Execute the query
            $results = $conn->query($sql);
            return $results;
        } else
            return null;
    }
}
