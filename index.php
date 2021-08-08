<?php include("../blog/config.php") ?>
<?php include("../blog/header.php") ?>
<div id="feed">
    <?php
    $sql = "SELECT * FROM posts ORDER BY updated_at DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $dataArray = array();
        while ($row = $result->fetch_array()) {
    ?>
            <?php include('../blog/posts/item.php') ?>
    <?php
        }
    } else {
        $error = "No Data Found!";
    }
    ?>
</div>

<?php include("../blog/sidebar.php") ?>
<?php include("../blog/footer.php") ?>