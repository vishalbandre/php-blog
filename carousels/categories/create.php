<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<main class="container">
    <div class="content-area">
        <section class="content">
            <?php
            // to handle carousel
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['submit'] == 'create') :

                if (!empty($_POST['name'])) {
                    $name = htmlspecialchars($_POST['name']);
                } else {
                    $name = null;
                }

                $errors = array();

                if ($name == null) {
                    $errors['name'] = 'Name is required.';
                } else {
                    // also check for existing name
                    $check = "SELECT name FROM carousels_categories WHERE name='" . $name . "' LIMIT 1";
                    $result = $conn->query($check);
                    if ($result->num_rows > 0) {
                        $errors['name'] = 'This name already exists.';
                    }
                }

                if (count($errors) <= 0) {
                    $sql = "INSERT INTO carousels_categories (name) VALUES('" . $name . "')";

                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['message'] = '<div class="success">Category Added.</div>';
                        header("Location: /carousels/categories/");
                    } else {
                        $_SESSION['message'] = '<div class="warning">Failed to add category.</div>';
                    }
                }

            endif; ?>

            <?php
            if (count($errors) > 0) {
                foreach ($errors as $key => $value) {
                    echo '<div class="form-error">' . $value . '</div>';
                }
            }
            ?>

            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION["message"]);
            }
            ?>

            <!-- Carousel Form -->
            <form action="" method="POST" class="form form-small">
                <h3 class="form-caption">Add New Category</h3>
                <div class="form-inner">
                    <fieldset>
                        <label>Category Name: </label><br>
                        <input type="text" name="name" class="<?php if (isset($errors['name'])) : ?>input-error<?php endif; ?>" value="<?php echo $name; ?>" />
                    </fieldset>
                    <fieldset>
                        <button type="submit" name="submit" value="create" class="button button-ok">Save Category</button>
                    </fieldset>
                </div>
            </form>
        </section>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>