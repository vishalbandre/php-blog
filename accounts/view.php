<?php
require "../vendor/autoload.php";

use Carousel\Carousel;

error_reporting(-1);
ini_set('display_errors', 'On');
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
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>