<?php
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
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<!-- Carousel -->
<?php
$sql_carousel = "SELECT * FROM carousels WHERE category_id=12 ORDER BY updated_at DESC LIMIT 1";
$result_car = $conn->query($sql_carousel);

$caraousel_id = null;
if ($result_car->num_rows > 0) {
    while ($row = $result_car->fetch_array()) {
        $caraousel_id = $row['id'];
    }
}

if ($caraousel_id != null) {

    $q = "SELECT * FROM images LEFT OUTER JOIN carousels_images ON images.id = carousels_images.image_id AND carousels_images.carousel_id = $caraousel_id LEFT OUTER JOIN carousels ON carousels_images.carousel_id = carousels.id where carousels.id IS NOT NULL";
    $result_images = $conn->query($q);

    if ($result_images->num_rows > 0) {
        if ($result_images->num_rows == 1) {
            while ($row_image = $result_images->fetch_array()) {
?>
                <img class="banner" src="/uploads/images/<?php echo $row_image['imgpath']; ?>" alt="<?php echo $row_image['caption']; ?>">
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

<?php

?>

<main class="container">
    <section class="feed">
        <?php

        $total_pages = "SELECT COUNT(*) FROM posts";
        $result = mysqli_query($conn, $total_pages);
        $total_rows = mysqli_fetch_array($result)[0];
        $pages = ceil($total_rows / $per_page);

        $sql = "SELECT * FROM posts ORDER BY updated_at DESC LIMIT $offset, $per_page";
        $result = $conn->query($sql);
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

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>