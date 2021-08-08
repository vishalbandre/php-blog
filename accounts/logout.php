<?php include("../config.php") ?>
<?php include("../header.php") ?>
<div id="content">
<?php 
if(isset($_COOKIE['blog_user'])) {
    setcookie('blog_user', '', time()-3600, '/');
}
?>
<a href="/accounts/login.php">Login Again</a>
</div>

<?php include("../sidebar.php") ?>
<?php include("../footer.php") ?>