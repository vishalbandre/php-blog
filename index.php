<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Use Post namespace to interact with posts table
use Carousel\Carousel;
use Post\Post;

// Use Language namespace to handle the languages
use Admin\Language;
use Admin\Translation;

use Carousel\Category\Category;

if (isset($_GET['lang'])) {
    $language_prefix = $_GET['lang'];
} else {
    $language_prefix = 'en';
}

$language = new Language();
$lang_id = $language->getIdByPrefix($language_prefix);

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

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}

$language = new Language();
$site_lang_id = $language->getIdByPrefix($site_lang);
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
// Get category id based on category name
$category = new Category();
$category_id = $category->getCategoryIdByName('Homepage');

$result_car = $carousel->getByCategoryId($category_id);

$caraousel_id = null;
if ($result_car) {
    if ($result_car->num_rows > 0) {
        while ($row = $result_car->fetch_array()) {
            $caraousel_id = $row['id'];
        }
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
                    <!-- <div class="overlay">
                        <h1 class="caption"><?php echo $row_image['title']; ?></h1>
                        <p class="description"><?php echo $row_image['caption']; ?></p>
                    </div> -->
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
                        <span class="visually-hidden"><?php Translation::translate('Previous', $site_lang); ?></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"><?php Translation::translate('Next', $site_lang); ?></span>
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
                    if (isset($_SESSION['message']) && $_SESSION['message']) {
                        echo $_SESSION['message'];
                        unset($_SESSION["message"]);
                    }
                    ?>
                    <?php

                    // Get posts count
                    $post = new Post();
                    
                    // Get number of rows present in table
                    $total_rows = $post->count($site_lang_id);

                    // Get number of pages count for pagination
                    $pages = ceil($total_rows / $per_page);

                    // Get all posts
                    $post = new Post;

                    $result = $post->getAllPostsByLanguage($offset, $per_page, $site_lang_id);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_array()) {
                            if (isset($row['title']) && $row['title'] != '') {
                                require($_SERVER['DOCUMENT_ROOT'] . "/posts/item.php");
                            }
                        }
                    } else {
                    ?>
                        <p class="message">
                            <?php Translation::translate('Sorry! There are no posts yet.', $site_lang); ?>
                        </p>
                    <?php
                    }
                    ?>
                    <?php if ($result->num_rows > 0) { ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?page=1"><?php Translation::translate('First', $site_lang); ?></a></li>
                                <li class="page-item <?php if ($page <= 1) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($page <= 1) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?page=" . ($page - 1);
                                                                } ?>"><?php Translation::translate('Prev', $site_lang); ?></a>
                                </li>
                                <li class="page-item <?php if ($page >= $pages) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($page >= $pages) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?page=" . ($page + 1);
                                                                } ?>"><?php Translation::translate('Next', $site_lang); ?></a>
                                </li>
                                <li class="page-item"><a href="?page=<?php echo $pages; ?>" class="page-link"><?php Translation::translate('Last', $site_lang); ?></a></li>
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