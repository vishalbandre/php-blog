<?php require($_SERVER['DOCUMENT_ROOT']."/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/components/header.php") ?>
<div id="feed">
    <?php
    $sql = "SELECT * FROM posts ORDER BY updated_at DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $dataArray = array();
        while ($row = $result->fetch_array()) {
    ?>
            <?php include('./posts/item.php') ?>
    <?php
        }
    } else {
        $error = "No Data Found!";
    }
    ?>
</div>

<?php include($_SERVER['DOCUMENT_ROOT']."/components/sidebar.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/components/footer.php") ?>

</body>
</html>