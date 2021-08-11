<?php
if (empty($_GET['id']) || empty($_COOKIE['blog_user'])) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="content">
<?php 
if(isset($_COOKIE['blog_user'])) {
    setcookie('blog_user', '', time()-3600, '/');
}
?>
<a href="/accounts/login.php">Login Again</a>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>