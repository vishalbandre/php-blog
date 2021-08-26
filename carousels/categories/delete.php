<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $category_id = null;
    if (!isset($_GET['id']) || !isset($_SESSION['logged_in'])) {
        header('Location: /index.php');
    } else if (isset($_SESSION['is_admin'])) {
        $category_id = $_GET['id'];
    } else {
        header('Location: /index.php');
    }
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

            $sql_carousels = "SELECT * FROM carousels WHERE category_id=$id";
            $result_carousels = $conn->query($sql_carousels);

            if ($result_carousels->num_rows > 0) {
                $_SESSION['message'] = '<div class="warning">Category in use. You can\'t delete this category.</div>';
                header('Location: /carousels/categories/view.php?id=' . $id);
                die();
            } else {

                $sql_delete = "DELETE FROM carousels_categories WHERE id=$id";
                if ($conn->query($sql_delete) === TRUE) {
                    $_SESSION['message'] = '<div class="success">Category Deleted.</div>';
                    header("Location: /carousels/categories/");
                    die();
                } else {
                    $_SESSION['message'] = '<div class="warning">Category Deletion Failed.</div>';
                }
            }
            $conn->close();
        } else {
            header('Location: /carousels/categories/view.php?id=' . $id);
        }
    else : ?>

        <?php
        $check = "SELECT * FROM carousels_categories WHERE id='" . $_GET['id'] . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
        ?>
            <p>Are you sure to delete this category?</p>
            <?php foreach ($result as $key => $value) : ?>
                <form action="/carousels/categories/delete.php" method="POST">
                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <p>
                        <button type="submit" name="submit" value="yes" class="button button-ok">Yes</button>
                        <button type="submit" name="submit" value="no" class="button button-ok">No</button>
                    </p>
                </form>
            <?php endforeach; ?>
        <?php
        }
        ?>
    <?php endif; ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>