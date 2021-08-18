<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<?php
if (empty($_GET['id']) || !$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="content">
    <?php
    if ($_SESSION['logged_in']) {
        session_unset();
        session_destroy();
    } else {
        header('Location: /index.php');
    }

    if (isset($_COOKIE['blog_user'])) {
        setcookie('blog_user', '', time() - 3600, '/');
    }
    ?>
    <a href="/accounts/login.php">Login Again</a>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>