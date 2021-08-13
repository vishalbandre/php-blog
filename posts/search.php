<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="feed">
    <?php
        $q = $_GET['q'];
    ?>
    <h3 class="caption">Search results for: <?php echo $q; ?></h3>
    <form action="/posts/search.php" class="top-search-form search-form" method="get">
        <input type="text" placeholder="Search" name="q" value="<?php echo $q; ?>" /><br />
        <input type="submit" value="Submit" class="search-btn" />
    </form>
    
    <?php
    $sql = "SELECT * from posts WHERE title LIKE '%$q%' OR description LIKE '%$q%' OR body LIKE '%$q%' ORDER BY updated_at DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            require($_SERVER['DOCUMENT_ROOT'] . "/posts/item.php");
        }
    } else {
        echo "Nothing found for this term!";
    }
    ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

</body>

</html>