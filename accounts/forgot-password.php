<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if ($_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="content">
    <h3 class="form-caption">Password Reset: </h3>
    <form action="password-reset-token.php" method="post" class="accounts-forms">
        <p>
        <label>Email address:</label>
        <input type="email" name="email">
        </p>
        <small>(Please enter an email you registered here account with.)</small>
        <input type="submit" name="password-reset-token" class="button">
    </form>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>