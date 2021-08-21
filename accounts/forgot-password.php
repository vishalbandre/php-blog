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

<main class="container">
    <section class="content">
        <form action="password-reset-token.php" method="post" class="accounts-forms">
            <h3 class="form-caption">Password Reset: </h3>
            <div class="form-inner">
                <fieldset>
                    <label>Email address:</label>
                    <input type="email" name="email">
                    <small>(Please enter an email you registered here account with.)</small>
                </fieldset>
                <fieldset>
                    <input type="submit" name="password-reset-token" value="Send Me Password Reset Mail" class="button">
                </fieldset>
            </div>
        </form>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>