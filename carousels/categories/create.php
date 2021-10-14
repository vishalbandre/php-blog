<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use namespace to interact with database table
use Carousel\Category\Category;
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/carousels/models/category.php") ?>

<?php
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
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

                            $data = array(
                                'name' => $name,
                            );

                            $category = new Category();

                            $q = $category->insert_category($data);

                            if ($q !== null) {
                                $_SESSION['message'] = '<div class="alert alert-success">Category Added.</div>';
                                header("Location: /carousels/categories/");
                                die();
                            } else {
                                $_SESSION['message'] = '<div class="alert alert-warning">Failed to add category.</div>';
                            }
                        }

                    endif; ?>

                    <?php
                    if (count($errors) > 0) {
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
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
                                <label class="form-label">Category Name: </label><br>
                                <input type="text" name="name" class="form-control m-0 <?php if (isset($errors['name'])) : ?>input-error<?php endif; ?>" value="<?php echo $name; ?>" />
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="btn btn-dark">Save Category</button>
                            </fieldset>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>