<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Language namespace to handle the languages
use Admin\Language;

use Post\Post;
use Admin\Translation;
use Admin\Term;

// Validations
use Validator\Validator;

// String to Slug Conversion
use Util\Util;
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
                        <a class="nav-link active" href="/admin/multilingual/langs/create">Add New Language</a>
                    </li>
                </ul>
                <section class="content">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['user'])) {
                            $user = htmlspecialchars($_POST['user']);
                        } else {
                            $user = null;
                        }

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

                        if ($name == null) {
                            $errors['name'] = 'Language name is required.';
                        } else {
                            // also check for existing languages
                            $check = "SELECT name FROM languages WHERE name='" . $name . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                $errors['name'] = 'This language is already exists.';
                            }
                        }

                        if ($prefix == null) {
                            $errors['prefix'] = 'Language prefix is required.';
                        } else {
                            // also check for existing languages
                            $check = "SELECT prefix FROM languages WHERE prefix='" . $prefix . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                $errors['prefix'] = 'This language prefix already exists.';
                            }
                        }

                        if (count($errors) <= 0) {

                            $data = array(
                                'name' => $name,
                                'prefix' => $prefix
                            );

                            // Create language
                            $language = new Language();
                            $q = $language->insert($data);

                            if ($q !== null) {
                                // Migrate posts table with new language entry for all existing posts
                                // Create a post object
                                $post = new Post();

                                // Get list of posts whose base_post_id field is null
                                $base_posts = $post->getAllBasePosts();

                                if ($base_posts) {
                                    foreach ($base_posts as $base_post) {
                                        $data_array = array(
                                            'title' => NULL,
                                            'user' => $user,
                                            'description' => NULL,
                                            'body' => NULL,
                                            'slug' => NULL,
                                            'base_post_id' => $base_post['id'],
                                            'language_id' => $q
                                        );
                                        $temp = $post->insert($data_array);
                                    }
                                }

                                // Migrate translations table with new language entry for all existing translations
                                // Get all terms by creating an object
                                $term = new Term();
                                $terms = $term->getAll();

                                if ($terms) {
                                    $translation = new Translation();
                                    foreach ($terms as $term) {
                                        $data_array = array(
                                            'translation' => NULL,
                                            'term_id' => $term['id'],
                                            'language_id' => $q
                                        );
                                        
                                        $t = $translation->insert($data_array);
                                    }
                                }

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
                    <form action="/admin/multilingual/langs/create" method="POST" class="form form-small">
                        <h3 class="form-caption">New Language</h3>
                        <div class="form-inner">
                            <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                            <fieldset>
                                <label class="form-label">Language Name: </label><br>
                                <input type="text" name="name" class="form-control m-0 <?php if (isset($errors['name'])) : ?>input-error<?php endif; ?>" value="<?php if (isset($name)) : echo $name;
                                                                                                                                                                endif; ?>" />
                            </fieldset>
                            <fieldset>
                                <label class="form-label">Prefix: </label><br>
                                <input type="text" name="prefix" class="form-control m-0 <?php if (isset($errors['prefix'])) : ?>input-error<?php endif; ?>" value="<?php if (isset($prefix)) : echo $prefix;
                                                                                                                                                                    endif; ?>" />
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="btn btn-dark">Save Language</button>
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