<?php
require "../vendor/autoload.php";

use Carousel\Carousel;

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET['user'])) {
    header('Location: /index.php');
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");

$u = $_GET['user'];
$sql = "SELECT * FROM users WHERE username='$u'";
$result = $conn->query($sql);
if ($result->num_rows <= 0) {
    header('HTTP/1.0 404 Not Found', TRUE, 404);
    die(header('location: /errors/404.php'));
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<style>
    .carousel {
        margin-bottom: 50px;
    }

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
$result_car = $carousel->getByAttribute('category_id', 13);

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

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="feed">
                    <?php
                    $user = $_GET['user'];
                    ?>
                    <?php
                    if (isset($_SESSION['message'])) {
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
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>