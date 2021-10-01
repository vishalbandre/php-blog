<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET['email'])) {
    header('Location: /index.php');
} else {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    }
}

// Get namespace
use Newsletter\Subscriber;

?>

<?php
// Import the dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php");

// Autoload required classes
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
?>

<main class="container">
    <div class="content-area">
        <section class="content">
            <?php

            /**
             * Check if the email is present in records
             */
            $s = new Subscriber();
            $check = $s->checkExistingSubscriber($email);

            if ($check) {

                /**
                 * Create a subscriber
                 */
                $subscriber = new Subscriber();
                $q = $subscriber->unsubscribe($email);

                // If unsubscription successfully happens, send user to homepage after 10 seconds.
                if ($q) {
            ?>
                    <div>
                        You\'ve successfully unsubscribed from our newsletter. You can subscribe us back any time from <a href="/newsletters/subscribe.php">here</a>.
                        <div class="wait">Within 10 seconds you will be redirected back to the homepage. If it doesn't redirect, please <a href="/">click here.</a></div>
                        <?php header("Refresh:10; url=/"); ?>
                    </div>
            <?php
                }
            } else {
                header('Location: /');
                die();
            }
            ?>
        </section>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>