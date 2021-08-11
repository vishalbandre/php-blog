<?php
if (empty($_GET['id']) || empty($_COOKIE['blog_user'])) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="content">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') :
        if (!empty($_POST['id'])) {
            $id = htmlspecialchars($_POST['id']);
        } else {
            $id = null;
        }

        if ($_POST['submit'] == 'yes') {
            $sql = "DELETE FROM posts WHERE id='$id'";

            if ($conn->query($sql) === TRUE) {
                header('Location: /index.php');
            } else {
                echo $conn->error;
                echo "Error";
            }
            $conn->close();
        } else {
            header('Location: /posts/article.php?id=' . $id);
            // echo "Yes: " . $_POST['submit'];
        }
    else : ?>

        <?php
        $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
        ?>
            <p>Are you sure to delete this article?</p>
            <?php foreach ($result as $key => $value) : ?>
                <form action="/posts/delete.php" method="POST">
                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <p>
                        <button type="submit" name="submit" value="yes" class="button button-ok">Yes</button>
                        <button type="submit" name="submit" value="no" class="button button-ok">No</button>
                    </p>
                </form>
            <?php endforeach; ?>
        <?php
        } else {
            $error = "Something went wrong. " . $conn->error;
            echo $conn->error;
        }
        ?>
    <?php endif; ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>