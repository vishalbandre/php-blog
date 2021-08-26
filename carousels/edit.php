<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");
$carousel_id = null;
if (!isset($_GET['id']) || !isset($_SESSION['logged_in']) || !isset($_GET['user'])) {
    header('Location: /index.php');
} else if (isset($_SESSION['is_admin']) || $_SESSION['user'] == $_GET['user']) {
    $user = trim($_GET['user']);
    $id = $_GET['id'];
    $check = "SELECT * FROM carousels WHERE user='$user' AND id=$id";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        $carousel_id = $_GET['id'];
    } else if (isset($_SESSION['is_admin'])) {
        $id = $_GET['id'];
        $check = "SELECT * FROM carousels WHERE id=$id";
        $result = $conn->query($check);
        $carousel_id = $_GET['id'];
    } else {
        header('Location: /index.php');
    }
} else {
    header('Location: /index.php');
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
    <section class="content">
        <?php
        // to handle carousel
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['submit'] == 'edit') :
            if (!empty($_POST['user'])) {
                $user = htmlspecialchars($_POST['user']);
            } else {
                $user = null;
            }

            if (!empty($_POST['id'])) {
                $id = htmlspecialchars($_POST['id']);
            } else {
                $id = null;
            }

            if (!empty($_POST['title'])) {
                $title = htmlspecialchars($_POST['title']);
            } else {
                $title = null;
            }

            if (!empty($_POST['category'])) {
                $category = htmlspecialchars($_POST['category']);
            } else {
                $category = null;
            }

            if (!empty($_POST['description'])) {
                $description = htmlspecialchars($_POST['description']);
            } else {
                $description = null;
            }

            $errors = array();

            if ($title == null) {
                $errors['title'] = 'Title is required.';
            }

            $thumbs = null;

            if (count($_POST['thumb']) < 1) {
                $errors['thumb'] = 'Select at least 5 images for carousel.';
            } else {
                $thumbs = $_POST['thumb'];
            }

            if ($description == null) {
                $errors['description'] = 'Description is required.';
            }

            if ($category == null) {
                $errors['category'] = 'Carousel category is required.';
            }

            $error_deletion_failed = null;

            if (count($errors) <= 0) {
                $sql_cat = "SELECT id FROM carousels_categories WHERE name='$category' LIMIT 1";
                $result_cat = $conn->query($sql_cat);

                $cat_id = null;
                if ($result_cat->num_rows > 0) {
                    while ($row_cat = $result_cat->fetch_array()) {
                        $cat_id = $row_cat['id'];
                    }
                }

                if ($thumbs) {
                    foreach ($_POST['thumb'] as $key) {
                        $sql_delete = "DELETE FROM carousels_images WHERE carousel_id=$id";
                        if (!$conn->query($sql_delete) === TRUE) {
                            $error_deletion_failed = true;
                        }
                    }

                    $sql_update = "UPDATE carousels SET title=\"$title\", description=\"$description\", category_id=$cat_id WHERE id=$id";

                    if ($conn->query($sql_update) === TRUE) {

                        if ($error_deletion_failed) {
                            $_SESSION['message'] = '<div class="warning">Something went wrong!</div>';
                        } else {
                            $success = false;
                            foreach ($_POST['thumb'] as $key) {

                                $sql_relation = "INSERT INTO carousels_images (carousel_id, image_id) VALUES('" . $id . "', '" . $key  . "')";
                                if ($conn->query($sql_relation) === TRUE) {
                                    $success = true;
                                }
                            }
                            if ($success) {
                                $_SESSION['message'] = '<div class="success">Carousel saved successfully!</div>';
                                $path = "/carousels/view.php?id=$carousel_id";
                                header("Location: $path");
                                die();
                            } else
                                $_SESSION['message'] = '<div class="warning">Carousel saved but failed to add images, please try again!</div>';
                        }
                    } else {
                        $_SESSION['message'] = '<div class="warning">Update Failed!</div>';
                    }
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
        $check = "SELECT * FROM carousels WHERE id='" . $carousel_id . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) :
        ?>
            <?php while ($row_carousel = $result->fetch_array()) : ?>

                <!-- Carousel Form -->
                <form action="" method="POST" class="posts-forms carousels-forms">
                    <h3 class="form-caption">Edit Carousel</h3>
                    <div class="form-inner">
                        <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                        <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                        <fieldset>
                            <label>Title: </label>
                            <input type="text" name="title" class="<?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                    echo $title;
                                                                                                                                                } else {
                                                                                                                                                    echo $row_carousel['title'];
                                                                                                                                                } ?>" />
                        </fieldset>

                        <fieldset>
                            <label>Category: </label>
                            <?php
                            $cat_id = $row_carousel['category_id'];
                            $sql_cat = "SELECT name FROM carousels_categories WHERE id=$cat_id";
                            $result_cat = $conn->query($sql_cat);

                            $cat_name = null;
                            if ($result_cat->num_rows > 0) {
                                while ($row_cat = $result_cat->fetch_array()) {
                                    $cat_name = $row_cat['name'];
                                }
                            }

                            $sql_cats = "SELECT * FROM carousels_categories";
                            $result_cats = $conn->query($sql_cats);
                            if ($result_cats->num_rows > 0) { ?>
                                <select name="category" class="category-dropdown <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>">
                                    <option value="" disabled="disabled" selected="selected">Please select a carousel category</option>
                                    <?php
                                    while ($row_cats = $result_cats->fetch_array()) {
                                    ?>
                                        <option value="<?php echo $row_cats['name']; ?>" <?php if (isset($category)) {
                                                                                                if ($category == $row_cats['name']) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                            } else {
                                                                                                if ($cat_name == $row_cats['name']) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                            } ?>><?php echo $row_cats['name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php
                            } ?>
                        </fieldset>
                        <fieldset>
                            <small>Don't find desired images?
                                <a href="/carousels/upload.php">Upload from here</a>
                            </small>
                            <!-- list of files to select from -->
                            <?php
                            $sql_carousels_images = "SELECT * FROM carousels_images WHERE carousel_id = $carousel_id";
                            $sql_carousels_images_result = $conn->query($sql_carousels_images);
                            $old_images = array();
                            if ($sql_carousels_images_result->num_rows > 0) {
                                while ($row_old_images = $sql_carousels_images_result->fetch_array()) {
                                    array_push($old_images, $row_old_images['image_id']);
                                }
                            }

                            if (count($old_images) > 0) {
                                $ids = join(', ', $old_images);
                                $sql = "SELECT * FROM images ORDER BY FIELD(id, $ids) DESC";
                            } else {
                                $sql = "SELECT * FROM images ORDER BY updated_at DESC";
                            }

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                echo '<ul class="thumb-gallery">';
                                while ($row = $result->fetch_array()) {
                                    require($_SERVER['DOCUMENT_ROOT'] . "/carousels/selector-thumb.php");
                                }
                                echo '<ul>';
                            } else {
                            ?>
                                <p class="message">
                                    Sorry! There are no images yet.
                                </p>
                            <?php
                            }
                            ?>
                        </fieldset>

                        <fieldset>
                            <label>Description: </label><br>
                            <textarea name="description" class="<?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                    echo $description;
                                                                                                                                                                } else {
                                                                                                                                                                    echo $row_carousel['description'];
                                                                                                                                                                } ?></textarea>
                        </fieldset>
                        <fieldset>
                            <button type="submit" name="submit" value="edit" class="button button-ok">Save Post</button>
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