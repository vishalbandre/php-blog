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

<style>
    .carousel .carousel-item {
        height: 350px;
    }

    .carousel-item img {
        position: absolute;
        top: 0;
        left: 0;
        min-height: 350px;
        object-fit: cover;
        filter: brightness(65%);
    }
</style>

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

            <div class="col-md-12">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-ride="carousel" data-interval="100">
                    <div class="carousel-inner">
                        <?php
                        $i = 1;
                        while ($row_image = $result_images->fetch_array()) {
                            $item_class = ($i == 1) ? 'carousel-item active' : 'carousel-item';
                        ?>
                            <div class="<?php echo $item_class; ?>">
                                <img src="/uploads/images/<?php echo $row_image['imgpath']; ?>" class="d-block w-100" alt="<?php echo $row_image['caption']; ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?php echo $row_image['title']; ?>abel</h5>
                                    <p><?php echo $row_image['caption']; ?></p>
                                </div>
                            </div>
                        <?php
                            $i++;
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
<?php
        }
    }
}
?>
<!-- Carousel end -->


<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
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
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?page=1">First</a></li>
                                <li class="page-item <?php if ($page <= 1) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($page <= 1) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?page=" . ($page - 1);
                                                                } ?>">Prev</a>
                                </li>
                                <li class="page-item <?php if ($page >= $pages) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($page >= $pages) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?page=" . ($page + 1);
                                                                } ?>">Next</a>
                                </li>
                                <li class="page-item"><a href="?page=<?php echo $pages; ?>" class="page-link">Last</a></li>
                            </ul>
                        </nav>
                    <?php } ?>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>