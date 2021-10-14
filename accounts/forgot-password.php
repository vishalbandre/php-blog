<?php
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>


<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="content">
                    <?php
                    if ($_SESSION['message']) {
                        echo $_SESSION['message'];
                        unset($_SESSION["message"]);
                    }
                    ?>

                    <form action="password-reset-token.php" method="post" class="form form-small">
                        <h3 class="form-caption">Password Reset: </h3>
                        <div class="form-inner">
                            <fieldset>
                                <label class="form-label">Email address:</label>
                                <input type="text" name="email" class="form-control m-0" required>
                                <small>(Please enter an email you registered here account with.)</small>
                            </fieldset>
                            <fieldset>
                                <input type="submit" name="password-reset-token" value="Send Me Password Reset Mail" class="btn btn-dark">
                            </fieldset>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>