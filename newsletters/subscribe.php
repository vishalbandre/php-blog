<?php
if (!isset($_SESSION)) {
    session_start();
}

// Get namespaces
use Newsletter\Subscriber;
use Email\Email;
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                /**
                 * If email is present in POST request sanitize it and put into $email else
                 * sel $email value to null
                 */
                if (!empty($_POST['email'])) {
                    $email = htmlspecialchars($_POST['email']);
                } else {
                    $email = null;
                }

                /**
                 * Array to push errors, if any, it can be used for showing the appripriate warnings
                 */
                $errors = array();
                $existing_subscriber = false;

                /**
                 * If email is not provided set the warning message
                 */
                if ($email == null) {
                    $errors['email'] = 'Email is required.';
                } else {
                    // also check for existing email
                    $s = new Subscriber();

                    $check = $s->checkExistingSubscriber($email);

                    /**
                     * If email already present in the system, set $errors with message
                     */
                    if ($check) {
                        $existing_subscriber = true;
                    }
                }

                if (count($errors) <= 0) {

                    if (!$existing_subscriber) {
                        /**
                         * Set array with required values
                         */
                        $data = array(
                            'email' => $email,
                        );

                        /**
                         * Create a subscriber
                         */
                        $subscriber = new Subscriber();
                        $q = $subscriber->subscribe($data);
                    } else {
                        $q = 'Existing User';
                    }

                    // If subscriber creation is successful, redirect them to greetings page.
                    if ($q !== null) {

                        $email = $email;
                        $subject = 'Newsletter Subscription';
                        $body = 'Thank you for subscribing to our newsletter. <br /> <hr /> You can unsubscribe to this newsletter at any time from here: <a href="blog/newsletters/unsubcribe.php?email=' . $email . '">unsubscribe</a>';

                        $mail = new Email();
                        $q = $mail->send($email, $subject, $body);

                        if ($q) {
                            header('Location: /newsletters/landing.php');
                            $_SESSION['message'] = '<div class="alert alert-success">You\'ve Successfully Subscribed To The Newsletter.</div>';
                            die();
                        }
                    } else {
                        // If subscriber creation fails, show warning
                        $_SESSION['message'] = '<div class="alert alert-warning">Sorry! Something went wrong.</div>';
                    }
                }
            } ?>
            <?php
            if (count($errors) > 0) {
                // Show errors, if there are any in $errors array
                foreach ($errors as $key => $value) {
                    echo '<div class="form-error">' . $value . '</div>';
                }
            }
            ?>
            <form action="" method="POST" class="form">
                <h3 class="form-caption">Newsletter Subscription</h3>
                <div class="form-inner">
                    <fieldset>
                        <input type="email" name="email" class="<?php if (isset($errors['email'])) : ?>input-error<?php endif; ?> subscriber-email" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                echo $email;
                                                                                                                                                            } ?>">
                        <button type="submit" name="submit" value="create" class="button button-ok">Subscribe to Newsletter</button>
                    </fieldset>
                </div>
            </form>
        </section>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>