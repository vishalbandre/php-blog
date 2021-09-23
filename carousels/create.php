<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use namespace to interact with database table
use Carousel\Carousel;
use Carousel\Image\CarouselImage;
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/carousel.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/carousel_image.php") ?>

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

                if (!empty($_POST['user'])) {
                    $user = htmlspecialchars($_POST['user']);
                } else {
                    $user = null;
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
                } else {
                    // also check for existing title
                    $check = "SELECT title FROM carousels WHERE title='" . $title . "' LIMIT 1";
                    $result = $conn->query($check);
                    if ($result->num_rows > 0) {
                        $errors['title'] = 'Carousel with this title already exists.';
                    }
                }

                $thumbs = null;

                if (count($_POST['thumb']) < 1) {
                    $errors['thumb'] = 'Select at least 1 image for carousel.';
                } else {
                    $thumbs = $_POST['thumb'];
                }

                if ($category == null) {
                    $errors['category'] = 'Carousel category is required.';
                }

                if ($description == null) {
                    $errors['description'] = 'Description is required.';
                }

                if (count($errors) <= 0) {
                    $sql_cat = "SELECT id FROM carousels_categories WHERE name='$category' LIMIT 1";
                    $result_cat = $conn->query($sql_cat);

                    $cat_id = null;
                    if ($result_cat->num_rows > 0) {
                        while ($row_cat = $result_cat->fetch_array()) {
                            $cat_id = $row_cat['id'];
                        }
                    }

                    $data = array(
                        'user' => $user,
                        'category_id' => $cat_id,
                        'title' => $title,
                        'description' => $description
                    );

                    $carousel = new Carousel();
                    $q = $carousel->insert($data);

                    if ($q != null) {
                        $id = $q;

                        $success = false;
                        foreach ($_POST['thumb'] as $key) {

                            $data = array(
                                'carousel_id' => $id,
                                'image_id' => $key
                            );

                            $carousel_image = new CarouselImage();

                            $ci = $carousel_image->insert($data);

                            if ($ci !== null) {
                                $success = true;
                            }
                        }
                        if ($success) {
                            $_SESSION['message'] = '<div class="success">Carousel saved successfully!</div>';
                            $path = "/carousels/view.php?id=$id";
                            header("Location: $path");
                            die();
                        } else
                            $_SESSION['message'] = '<div class="warning">Carousel saved but failed to add images, please try again!</div>';
                    } else {
                        $_SESSION['message'] = '<div class="warning">Something went wrong! Please try again.</div>';
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
            <form action="" method="POST" class="form">
                <h3 class="form-caption">New Carousel</h3>
                <div class="form-inner">
                    <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                    <fieldset>
                        <label>Title: </label>
                        <input type="text" name="title" class="<?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php echo $title; ?>" />
                    </fieldset>

                    <fieldset>
                        <label>Category: </label>
                        <?php
                        $sql = "SELECT * FROM carousels_categories";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) { ?>
                            <select name="category" class="category-dropdown <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>">
                                <option value="" disabled="disabled" selected="selected">Select category</option>
                                <?php
                                while ($row = $result->fetch_array()) {
                                ?>
                                    <option value="<?php echo $row['name']; ?>" <?php if ($category == $row['name']) : echo "selected";
                                                                                endif; ?>><?php echo $row['name']; ?></option>
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
                        $sql = "SELECT * FROM images ORDER BY updated_at DESC";
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
                        <textarea name="description" class="<?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php echo $description; ?></textarea>
                    </fieldset>
                    <fieldset>
                        <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
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