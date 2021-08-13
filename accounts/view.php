<?php
session_start();
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="feed">
    <?php
    $user = $_GET['user'];
    ?>
    <?php
        if($_SESSION['welcome-back']) {
            echo $_SESSION['welcome-back'];
            unset($_SESSION["welcome-back"]);
        }
    ?>
    <?php
        if($_SESSION['welcome']) {
            echo $_SESSION['welcome'];
            unset($_SESSION["welcome"]);
        }
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
        <p class="message">
            Sorry! There are no posts yet.
        </p>
        <p>
            <?php if($_SESSION['logged_in'] && $_SESSION['user'] == $_GET['user']) : ?> <a href="/posts/create.php">Add New Post</a><?php endif; ?>
        </p>
    <?php
    }
    ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>