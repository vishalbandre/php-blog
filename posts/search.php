<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="feed">
                    <?php
                    $q = $_GET['q'];
                    ?>
                    <h3 class="caption">Search results for: <?php echo $q; ?></h3>
                    <form action="/posts/search.php" class="offset-md-2 top-search-form search-form form-small" method="get">
                        <div class="input-group">
                            <input type="text" placeholder="Search" name="q" value="<?php echo $q; ?>" class="form-control m-0" />
                            <input type="submit" value="Submit" class="btn btn-dark" />
                        </div>
                    </form>

                    <?php
                    $total_pages = "SELECT COUNT(*) FROM posts";
                    $result = mysqli_query($conn, $total_pages);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $pages = ceil($total_rows / $per_page);

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
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>