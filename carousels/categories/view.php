<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET['id'])) {
    header('Location: /index.php');
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="container">
    <div class="content-area">
        <article class="single-post">
            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION["message"]);
            }
            ?>
            <?php
            $check = "SELECT * FROM carousels_categories WHERE id='" . $_GET['id'] . "' LIMIT 1";
            $result = $conn->query($check);
            if ($result->num_rows > 0) {
            ?>
                <?php while ($row = $result->fetch_array()) : ?>
                    <h2 class="post-title"><?php echo $row['name']; ?></h2>
                    <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']) : ?>
                        <ul class="actions">
                            <li class="edit">
                                <a href="/carousels/categories/edit.php?id=<?php echo $row['id']; ?>">Edit Category</a>
                            </li>
                            <li class="delete">
                                <a href="/carousels/categories/delete.php?id=<?php echo $row['id']; ?>">Delete Category</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php
            }
            ?>
        </article>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

</body>

</html>