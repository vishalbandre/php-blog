<?php
require "vendor/autoload.php";

// Use Post namespace to interact with posts table
use Carousel\Carousel;
use Post\Post;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page <= 0) {
    $page = 1;
}

$per_page = 4;
$offset = ($page - 1) * $per_page;

?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<!-- Carousel -->
<?php
$carousel = new Carousel();
$result_car = $carousel->getByAttribute('category_id', 12);

$caraousel_id = null;
if ($result_car->num_rows > 0) {
    while ($row = $result_car->fetch_array()) {
        $caraousel_id = $row['id'];
    }
}

if ($caraousel_id != null) {

    $carousel = new Carousel();

    $result_images = $carousel->getGallery($caraousel_id);

    if ($result_images->num_rows > 0) {
        if ($result_images->num_rows == 1) {
            while ($row_image = $result_images->fetch_array()) {
?>
                <div class="banner-container">
                    <img class="banner" src="/uploads/images/<?php echo $row_image['imgpath']; ?>" alt="<?php echo $row_image['caption']; ?>">
                    <div class="overlay">
                        <h1 class="caption"><?php echo $row_image['title']; ?></h1>
                        <p class="description"><?php echo $row_image['caption']; ?></p>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">

                        <?php
                        while ($row_image = $result_images->fetch_array()) {
                        ?>
                            <li class="splide__slide">
                                <img class="images" src="/uploads/images/<?php echo $row_image['imgpath']; ?>" alt="<?php echo $row_image['caption']; ?>">
                                <div class="overlay">
                                    <h1 class="caption"><?php echo $row_image['title']; ?></h1>
                                    <p class="description"><?php echo $row_image['caption']; ?></p>
                                </div>
                            </li>

                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
<?php
        }
    }
}
?>
<!-- Carousel end -->

<main class="container">
    <div class="content-area">
        <section class="feed">
            <?php
            if ($_SESSION['message']) {
                echo $_SESSION['message'];
                unset($_SESSION["message"]);
            }
            ?>
            <?php

            // Get posts count
            $post = new Post();
            $total_pages = $post->count();

            // Get number of rows present in table
            $total_rows = mysqli_fetch_array($total_pages)[0];

            // Get number of pages count for pagination
            $pages = ceil($total_rows / $per_page);

            // Get all posts
            $post = new Post;
            $result = $post->getAll($offset, $per_page);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    require($_SERVER['DOCUMENT_ROOT'] . "/posts/item.php");
                }
            } else {
            ?>
                <p class="message">
                    Sorry! There are no posts yet.
                </p>
            <?php
            }
            ?>
            <?php if ($result->num_rows > 0) { ?>
                <ul class="pagination">
                    <li><a href="?page=1">First</a></li>
                    <li class="<?php if ($page <= 1) {
                                    echo 'disabled';
                                } ?>">
                        <a href="<?php if ($page <= 1) {
                                        echo '#';
                                    } else {
                                        echo "?page=" . ($page - 1);
                                    } ?>">Prev</a>
                    </li>
                    <li class="<?php if ($page >= $pages) {
                                    echo 'disabled';
                                } ?>">
                        <a href="<?php if ($page >= $pages) {
                                        echo '#';
                                    } else {
                                        echo "?page=" . ($page + 1);
                                    } ?>">Next</a>
                    </li>
                    <li><a href="?page=<?php echo $pages; ?>">Last</a></li>
                </ul>
            <?php } ?>
        </section>
    </div>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>