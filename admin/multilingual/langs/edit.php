<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Use Language namespace to handle the languages
use Admin\Language;

if (!isset($_GET['lang']) || !$_SESSION['is_admin']) {
    // If language is not present or authenticated user is not an admin one redirect
    // them to homepage.
    header('Location: /');
} else {
    $_prefix = $_GET['lang'];
    $language = new Language();
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
                <ul class="nav nav-tabs mt-5">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/admin/multilingual/langs/">All Languages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/multilingual/langs/create">Add New Language</a>
                    </li>
                </ul>
                <section class="content">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['name'])) {
                            $name = htmlspecialchars($_POST['name']);
                        } else {
                            $name = null;
                        }

                        if (!empty($_POST['prefix'])) {
                            $prefix = htmlspecialchars($_POST['prefix']);
                        } else {
                            $prefix = null;
                        }

                        $errors = array();

                        // Making sure that a value for language name field exists.
                        if ($name == null) {
                            $errors['name'] = 'Language name is required.';
                        }

                        // also check for existing languages
                        $results_languages = $language->get($_prefix);

                        // If language with entered prefix exists.
                        if (isset($results_languages)) {
                            if ($results_languages->num_rows > 0) {
                                // Iterate over the retrieved result
                                while ($row = $results_languages->fetch_assoc()) {
                                    /* If the language entered by user is same as
                                    * currently retrieved record, do not set the
                                    * errors flag and let the system continue to update the record.
                                    */
                                    if ($row['name'] != $name) {
                                        /**
                                         * If the language entered by user and language returned by
                                         * query result is not same, make sure this value is not exist
                                         * for some other record. If this is so, set the $errors flag
                                         * with appropriate error message indicating that this particular
                                         * value is already taken.
                                         */
                                        $check = $language->getByLanguageName($name);
                                        if (isset($check)) {
                                            $errors['name'] = 'Language name already exists.';
                                        }
                                    }
                                }
                            }
                        }

                        if ($prefix == null)
                            $errors['prefix'] = 'Language prefix is required.';

                        $results_temp = $language->get($_prefix);
                        
                        if ($results_temp->num_rows > 0) {
                            while ($row = $results_temp->fetch_assoc()) {
                                if ($row['prefix'] != $prefix) {
                                    $check = $language->get($prefix);
                                    if (isset($check)) {
                                        $errors['prefix'] = 'This language prefix already exists.';
                                    }
                                }
                            }
                        }

                        if (isset($errors) && count($errors) <= 0) {

                            $data = array(
                                'name' => $name,
                                'prefix' => $prefix
                            );

                            // Create language
                            $language = new Language();
                            $q = $language->update($data, $_prefix);

                            // If post is created successfully, redirect to homepage.
                            if ($q !== null) {
                                header('Location: /admin/multilingual/langs/');
                                $_SESSION['message'] = '<div class="alert alert-success">Language saved successfully.</div>';
                                die();
                            } else {
                                // If post creation fails, show warning
                                $_SESSION['message'] = '<div class="alert alert-warning">Failed to save the language.</div>';
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

                    <?php
                    $result = $language->get($_prefix);
                    if ($result->num_rows > 0) {
                    ?>
                        <?php while ($row = $result->fetch_array()) : ?>
                            <form action="" method="POST" class="form">
                                <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                                <h3 class="form-caption">Edit Language</h3>
                                <div class="form-inner">
                                    <fieldset>
                                        <label class="form-label">Language Name: </label><br>
                                        <input type="text" name="name" class="form-control m-0 <?php if (isset($errors['name'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                            echo $name;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo $row['name'];
                                                                                                                                                                        } ?>" />
                                    </fieldset>
                                    <fieldset>
                                        <label class="form-label">Prefix: </label><br>
                                        <input type="text" name="prefix" class="form-control m-0 <?php if (isset($errors['prefix'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                                echo $prefix;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo $row['prefix'];
                                                                                                                                                                            } ?>" />
                                    </fieldset>
                                    <fieldset>
                                        <button type="submit" name="submit" value="create" class="btn btn-dark">Save Language</button>
                                    </fieldset>
                                </div>
                            </form>
                    <?php
                        endwhile;
                    } else {
                        header('Location: /admin/multilingual/langs');
                    }
                    ?>
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