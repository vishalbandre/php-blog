<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page <= 0) {
    $page = 1;
}

$per_page = 2;
$offset = ($page - 1) * $per_page;
?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
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
        ?>
                <?php include('./posts/item.php') ?>
            <?php
            }
            ?>
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
        <?php
        } else {
            $error = "There are no posts added yet!";
        }
        ?>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

</body>

</html>