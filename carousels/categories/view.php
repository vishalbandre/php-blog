<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use namespace to interact with database table
use Carousel\Category\Category;

if (empty($_GET['id'])) {
    header('Location: /index.php');
} else {
    $cat_id = $_GET['id'];
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/category.php") ?>

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
            // Get category from the database
            $result = Category::get_category($cat_id);

            // Display category data if it exists
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