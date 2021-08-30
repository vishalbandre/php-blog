<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php
$category_id = null;
if (!isset($_GET['id']) || !isset($_SESSION['logged_in'])) {
    header('Location: /index.php');
} else if (isset($_SESSION['is_admin'])) {
    $category_id = $_GET['id'];
} else {
    header('Location: /index.php');
}
?>
<main class="container">
    <section class="content">
        <?php
        // to handle carousel
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['submit'] == 'edit') :

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
                    $errors['name'] = 'Category with this name already exists.';
                }
            }

            if (isset($errors) && count($errors) <= 0) {
                $sql = "UPDATE carousels_categories SET name='$name' WHERE id=$category_id";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['message'] = '<div class="success">Category Saved.</div>';
                    header("Location: /carousels/categories/");
                    die();
                } else {
                    $_SESSION['message'] = '<div class="warning">Failed to save category.</div>';
                }
            }

        endif; ?>

        <?php
        if (isset($errors) && count($errors) > 0) {
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

        <?php
        $check = "SELECT * FROM carousels_categories WHERE id='" . $category_id . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) :
        ?>
            <?php while ($row = $result->fetch_array()) : ?>

                <!-- Carousel Form -->
                <form action="" method="POST" class="posts-forms accounts-forms">
                    <h3 class="form-caption">Edit Category</h3>
                    <div class="form-inner">
                        <fieldset>
                            <label>Category Name: </label><br>
                            <input type="text" name="name" class="<?php if (isset($errors['name'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                echo $name;
                                                                                                                                            } else {
                                                                                                                                                echo $row['name'];
                                                                                                                                            } ?>" />
                        </fieldset>
                        <fieldset>
                            <button type="submit" name="submit" value="edit" class="button button-ok">Save Category</button>
                        </fieldset>
                    </div>
                </form>

        <?php endwhile;
        endif; ?>

    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>