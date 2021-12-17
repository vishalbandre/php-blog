<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: /errors/forbidden.php'));
}
?>

<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<article class="item">
    <a href="/carousels/view/<?php echo $row['id']; ?>">
        <h2 class="title">
            <?php echo $row["title"]; ?>
        </h2>
    </a>
    <section class="author">
        <strong>Posted by: </strong>
        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
        <strong> on:</strong>
        <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
    </section>
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
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
        <ul class="actions mt-2">
            <li>
                <a href="/carousels/edit/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-primary">Edit</a>
            </li>
            <li>
                <a href="/carousels/delete/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-danger">Delete</a>
            </li>
        </ul>
    <?php endif; ?>
</article>