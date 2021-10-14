<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use namespace to interact with database table
use Carousel\Carousel;

if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}

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
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/carousel.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="feed">
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION["message"]);
                    }
                    ?>

                    <?php
                    // Get carousels count
                    $carousel = new Carousel();
                    $total_pages = $carousel->count();

                    // Get number of rows present in table
                    $total_rows = mysqli_fetch_array($total_pages)[0];

                    // Get number of pages count for pagination
                    $pages = ceil($total_rows / $per_page);

                    // Get all carousels
                    $carousel = new Carousel();
                    $result = $carousel->getAll($offset, $per_page);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_array()) {
                            require($_SERVER['DOCUMENT_ROOT'] . "/carousels/item.php");
                        }
                    } else {
                    ?>
                        <p class="message">
                            Sorry! There are no carousels yet.
                        </p>
                    <?php
                    }
                    ?>
                    <?php if ($result->num_rows > 3) { ?>
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