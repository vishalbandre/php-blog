<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container">
    <section class="feed">
        <?php
        $user = $_GET['user'];
        ?>
        <?php
        if ($_SESSION['message']) {
            echo $_SESSION['message'];
            unset($_SESSION["message"]);
        }
        ?>
        <?php if ($_SESSION['logged_in'] && $_SESSION['is_admin']) : ?>
            <a href="/accounts/edit.php?user=<?php echo $_GET['user']; ?>">Edit This Profile*</a><br>
        <?php endif; ?>

        <h3 class="caption">All Articles by <?php echo $user; ?></h3>
        <?php
        $user = $_GET['user'];
        $sql = "SELECT * FROM posts WHERE user='$user' ORDER BY updated_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $dataArray = array();
            while ($row = $result->fetch_array()) {
                require($_SERVER['DOCUMENT_ROOT'] . "/posts/item.php");
            }
        } else {
        ?>
            <p class="message">
                Sorry! There are no posts yet.
            </p>
            <p>
                <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $_GET['user']) : ?> <a href="/posts/create.php">Add New Post</a><?php endif; ?>
            </p>
        <?php
        }
        ?>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>