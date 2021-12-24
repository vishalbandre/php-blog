<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Admin\Translation;
use Admin\Term;

// Use Language namespace to handle the languages
use Admin\Language;

if (!isset($_GET['id']) || !$_SESSION['is_admin']) {
    // If language is not present or authenticated user is not an admin one redirect
    // them to homepage.
    header('Location: /');
} else {
    $id = $_GET['id'];
    $t = new Term();
    $term_results = $t->get($id);
    $term = $term_results->fetch_assoc();
}

?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

<?php
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar-admin.php") ?>
        </div>
        <div class="col-md-9">
            <div class="content-area">
                <h6 class="display-6 mt-3">UI/String Translations</h6>
                <a class="btn btn-primary" aria-current="page" href="/admin/multilingual/translations/">Back To All Terms</a>

                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/admin/multilingual/translations/">All Terms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/multilingual/translations/create-term">Add New Term</a>
                    </li>
                </ul>
                <section class="content">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['term'])) {
                            $term = trim(htmlspecialchars($_POST['term']));
                        } else {
                            $term = null;
                        }

                        $errors = array();

                        if ($term == null) {
                            $errors['term'] = 'Term is required.';
                        } else {
                            // also check for existing term
                            $check = "SELECT term FROM terms WHERE term='" . $term . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                $errors['term'] = 'This term already exists.';
                            }
                        }

                        if (isset($errors) && count($errors) <= 0) {

                            $data = array(
                                'term' => $term
                            );

                            // Create a new term object
                            $term_update = new Term();
                            $q = $term_update->update($data, $id);

                            // If post is created successfully, redirect to homepage.
                            if ($q !== null) {
                                header('Location: /admin/multilingual/translations/');
                                $_SESSION['message'] = '<div class="alert alert-success">Term saved successfully.</div>';
                                die();
                            } else {
                                // If post creation fails, show warning
                                $_SESSION['message'] = '<div class="alert alert-warning">Failed to save the term.</div>';
                            }
                        }
                    } ?>
                    <?php
                    if (isset($errors) && count($errors) > 0) {
                        echo '&nbsp;';
                        // Show errors, if there are any in $errors array
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
                        }
                    }
                    ?>

                    <form action="" method="POST" class="form form-small">
                        <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                        <h3 class="form-caption">Edit Term</h3>
                        <div class="form-inner">
                            <fieldset>
                                <label class="form-label"><span dir="ltr">Term:</span></label><br>
                                <input type="text" name="term" class="form-control m-0 <?php if (isset($errors['term'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                    echo $term;
                                                                                                                                                                } else {
                                                                                                                                                                    echo $term['term'];
                                                                                                                                                                } ?>" />
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="btn btn-dark">Save Term</button>
                            </fieldset>
                        </div>
                    </form>

                </section>
            </div>
        </div>
    </div>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>