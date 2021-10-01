<?php

namespace Newsletter;

// Autoload required classes
require_once(dirname(__DIR__) . "/vendor/autoload.php");

// Get namespaces
use Newsletter\Subscriber;
use Post\Post;
use Email\Email;

/**
 * Class to handle newsletter
 */
class Newsletter
{
    public function __construct()
    {
    }

    /**
     * Retrive subscribers from database.
     */
    public function prepareMailingList()
    {
        // Subscriber instance
        $subscribers = new Subscriber();

        $result = $subscribers->getSubscribers();

        // Array to store emails
        $emails = array();

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    // Push available email to mailing list array
                    array_push($emails, $row['email']);
                }
            }
        }

        // Return array
        return $emails;
    }

    /**
     * Retrive posts to be included in newsletter
     */
    public function getPosts()
    {
        // Get all posts
        $post = new Post;

        $result = $post->getAllByLimit(5);

        // Array to store posts
        $posts = array();

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $post = '
                    <div class="post">
                        <h3 class="title">' . $row["title"] . '</h3>
                        <div class="description">' . $row["description"] . '</div>
                        <div class="read-more"><small><a href="http://' . $_SERVER['SERVER_NAME'] . '/posts/article.php?id=' . $row['id'] . '">Read More</a></small></div>
                    </div>
                ';
                    // Push available post to posts array
                    array_push($posts, $post);
                }
            }
        }

        // Return posts array
        return $posts;
    }

    /**
     * Construct newsletter
     */
    public function constructNewsletter()
    {
        // Get all posts
        $posts = $this->getPosts();

        // Prepare a newsletter template
        $body = '<div class="newsletter">';
        $body .= '<h2>PHP Blog Newsletter</h2><br />';
        foreach ($posts as $post)
            $body .= $post;
        $body .= '</div>';
        return $body;
    }


    /**
     * Send newsletters to subscribers
     */
    public function send()
    {
        $emails = $this->prepareMailingList();

        // To record email ids that have been catered
        $emails_success = array();

        if (count($emails) > 0) {
            $subject = 'Email Newsletter | PHP Blog';

            // Newsletter body
            $body = $this->constructNewsletter();

            // Create an email instance
            $mail = new Email();

            foreach ($emails as $email) {
                // Send email
                $q = $mail->send($email, $subject, $body);

                if ($q) {
                    array_push($emails_success, $email);
                }
            }
        }

        // Return the list of emails (newsletter successfully sent to)
        return $emails_success;
    }
}
