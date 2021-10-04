<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<?php
// Import the dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php");
?>

<main class="container">
    <div class="content-area">
        <section class="content">
            <div class="hero">
                <?php
                if ($_SESSION['message']) {
                    echo $_SESSION['message'];
                    unset($_SESSION["message"]);
                }
                ?>
                <h3 class="caption">Thanks for subscribing to our newsletter.</h3>
                <div class="description">Please check your email. You might have received a confirmation email. If you wish to unsubscribe to this newsletter please follow link provided in this email.</div>
                <div class="wait">Within 10 seconds you will be redirected back to the homepage. If it doesn't redirect, please <a href="/">click here.</a></div>
                <?php header("Refresh:10; url=/"); ?>
            </div>
        </section>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>