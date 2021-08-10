<?php
if (empty($_GET['user'])) {
    header('Location: /index.php');
}
?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="feed">
    <?php
    $user = $_GET['user'];
    ?>
    <h3>All Articles by <?php echo $user; ?></h3>
    <?php
    $user = $_GET['user'];
    $sql = "SELECT * FROM posts WHERE user='$user' ORDER BY updated_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $dataArray = array();
        while ($row = $result->fetch_array()) {
    ?>
            <?php include('../posts/item.php') ?>
        <?php
        }
    } else {
        ?>
        <div class="message">
            Sorry! There are no posts yet.
        </div>
        <div>
            <?php if (isset($_COOKIE['blog_user']) && $_COOKIE['blog_user'] == $_GET['user']) : ?> <a href="/posts/create.php">Add New Post</a><?php endif; ?>
        </div>
    <?php
    }
    ?>
</main>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>