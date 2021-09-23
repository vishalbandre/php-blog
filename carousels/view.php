<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use namespace to interact with database table
use Carousel\Carousel;

if (empty($_GET['id'])) {
    header('Location: /index.php');
} else {
    $car_id = $_GET['id'];
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/carousel.php") ?>

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
            // Get carousel from the database
            $carousel = new Carousel();

            $result = $carousel->get($car_id);

            if ($result->num_rows > 0) {
            ?>
                <?php while ($row = $result->fetch_array()) : ?>
                    <h2 class="post-title"><?php echo $row['title']; ?></h2>
                    <section class="author">
                        <strong>Posted by: </strong>
                        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
                        <strong> on:</strong>
                        <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
                    </section>
                    <br>
                    <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']) : ?>
                        <ul class="actions">
                            <li class="edit">
                                <a href="/carousels/edit.php?id=<?php echo $row['id']; ?>&user=<?php echo $row['user']; ?>">Edit</a>
                            </li>
                            <li class="delete">
                                <a href="/carousels/delete.php?id=<?php echo $row['id']; ?>&user=<?php echo $row['user']; ?>">Delete</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <summary class="post-description">
                        <?php echo $row['description']; ?>
                    </summary>
                    <div class="carousel-preview">
                        <strong>Preview</strong><br>
                        <?php
                        $id = $row['id'];
                        $q = "SELECT * FROM images LEFT OUTER JOIN carousels_images ON images.id = carousels_images.image_id AND carousels_images.carousel_id = $id LEFT OUTER JOIN carousels ON carousels_images.carousel_id = carousels.id where carousels.id IS NOT NULL";
                        $result_images = $conn->query($q);
                        if ($result_images->num_rows > 0) {
                            while ($row_image = $result_images->fetch_array()) {
                        ?>
                                <img class="thumb" src="/uploads/images/<?php echo $row_image['imgpath']; ?>" alt="<?php echo $row_image['caption']; ?>">
                        <?php
                            }
                        }
                        ?>
                    </div>
                <?php endwhile; ?>
            <?php
            }
            ?>
        </article>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

</body>

</html>