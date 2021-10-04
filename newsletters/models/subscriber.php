<?php

namespace Newsletter;

// Using Database namespace
use Database\Connection;

/**
 * Class to set common methods to interact with subscribers table.
 */

class Subscriber
{
    public function __construct($email = null)
    {
        $this->email = $email;
    }

    /**
     * Create email subscribtion
     */
    public function subscribe($data)
    {
        // Get a database connection
        $c = new Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->insert("subscribers", $data);
    }

    /**
     * Remove email entry from database to cancel the subscribtion
     */
    public function unsubscribe($email)
    {
        // Get a database connection
        $c = new Connection();

        // Return last inserted id if record inserted successfully, else null
        return $c->deleteByAttribute("subscribers", 'email', $email);
    }

    /**
     * Will return true if existing subscriber else false
     */
    public function checkExistingSubscriber($email)
    {
        // Get a database connection
        $c = new Connection();
        $check = $c->getByAttribute('subscribers', 'email', $email);
        if ($check)
            return true;
        else
            return false;
    }

    /**
     * Return all the subscribers.
     */
    public function getSubscribers($offset = null, $per_page = null, $recent=true)
    {
        // Get a database connection.
        $c = new Connection();

        return $c->getAll('subscribers', $offset, $per_page);
    }

}
