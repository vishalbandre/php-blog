<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}

// Use namespace to interact with database table
use Carousel\Category\Category;
?>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/category.php") ?>

<main class="container">
    <div class="content-area">
        <section class="feed">
            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION["message"]);
            }
            ?>

            <h3 class="caption">Carousel Categories</h3>

            <?php
            // Get all categories
            $result = Category::get_all_categories();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    require($_SERVER['DOCUMENT_ROOT'] . "/carousels/categories/item.php");
                }
            } else {
            ?>
                <p class="message">
                    Sorry! There are no categories yet.
                </p>
            <?php
            }
            ?>
        </section>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>