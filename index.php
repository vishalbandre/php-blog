<?php require($_SERVER['DOCUMENT_ROOT']."/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/components/header.php") ?>
<main class="feed">
    <?php
    $sql = "SELECT * FROM posts ORDER BY updated_at DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
    ?>
            <?php include('./posts/item.php') ?>
    <?php
        }
    } else {
        $error = "There are no posts added yet!";
    }
    ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/components/footer.php") ?>

</body>
</html>