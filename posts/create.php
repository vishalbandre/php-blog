<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;

// Use Language namespace to handle the languages
use Admin\Language;

use Admin\Translation;

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

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}

$language = new Language();
$lang_id = $language->getIdByPrefix($lang);
?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="content">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['user'])) {
                            $user = htmlspecialchars($_POST['user']);
                        } else {
                            $user = null;
                        }

                        if (!empty($_POST['title'])) {
                            $title = trim(htmlspecialchars($_POST['title']));
                        } else {
                            $title = null;
                        }

                        if (!empty($_POST['slug'])) {
                            $slug = trim(htmlspecialchars($_POST['slug']));
                        } else {
                            $slug = null;
                        }

                        if (!empty($_POST['body'])) {
                            $body = htmlspecialchars($_POST['body']);
                        } else {
                            $body = null;
                        }

                        if (!empty($_POST['description'])) {
                            $description = htmlspecialchars($_POST['description']);
                        } else {
                            $description = null;
                        }

                        $errors = array();

                        if ($title == null) {
                            $errors['title'] = Translation::translate('Title is required', $site_lang, true);
                        } else {
                            // also check for existing title
                            $check = "SELECT title FROM posts WHERE title='" . $title . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                $errors['title'] = Translation::translate('Article with this title already exists', $site_lang, true);
                            }
                        }

                        if ($slug != null) {
                            // also check for existing slug
                            $check = "SELECT id, slug FROM posts WHERE slug='" . $slug . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if (!is_null($id) && $row['id'] != $id) {
                                        $errors['slug'] = Translation::translate('Article with this slug already exists', $site_lang, true);
                                    }
                                }
                            }
                        }

                        if ($title != null && $title != '' && $slug == null && $slug != '') {
                            $util = new Util();

                            // Turn title to slug if not slug is provided
                            $slug = $util->stringToSlug($title);
                        } else {
                            $validator = new Validator();
                            $errors = $validator->validateSlug($slug);
                        }

                        if ($title == null) {
                            $errors['title'] = Translation::translate('Title is required', $site_lang, true);
                        }

                        if ($description == null) {
                            $errors['description'] = Translation::translate('Description is required', $site_lang, true);
                        }

                        if ($body == null) {
                            $errors['body'] = Translation::translate('Body is required', $site_lang, true);
                        }

                        if (count($errors) <= 0) {

                            $data = array(
                                'title' => $title,
                                'user' => $user,
                                'description' => $description,
                                'body' => $body,
                                'slug' => $slug,
                                'language_id' => $lang_id,
                            );

                            // Create post
                            $post = new Post();
                            $q = $post->insert($data);

                            // If post is created successfully, redirect to homepage.
                            if ($q !== null) {

                                $langs = $language->getAllLanguages();

                                if ($langs->num_rows > 0) {
                                    foreach ($langs as $lang) {

                                        if (!$lang['is_default']) {
                                            $multilingual_data = array(
                                                'title' => NULL,
                                                'user' => $user,
                                                'description' => NULL,
                                                'body' => NULL,
                                                'slug' => NULL,
                                                'base_post_id' => $q,
                                                'language_id' => $lang['id']
                                            );

                                            $post = new Post();
                                            $temp = $post->insert($multilingual_data);
                                        }
                                    }
                                }
                                header('Location: /');
                                $_SESSION['message'] = '<div class="alert alert-success">Article saved successfully.</div>';
                                die();
                            } else {
                                // If post creation fails, show warning
                                $_SESSION['message'] = '<div class="alert alert-warning">Failed to save the article.</div>';
                            }
                        }
                    } ?>
                    <?php
                    if (isset($errors) && count($errors) > 0) {
                        // Show errors, if there are any in $errors array
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
                        }
                    }
                    ?>
                    <form action="/posts/create.php" method="POST" class="form">
                        <h3 class="form-caption"><?php Translation::translate('New Post', $site_lang); ?></h3>
                        <div class="form-inner">
                            <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                            <fieldset>
                                <label class="form-label"><?php Translation::translate('Title', $site_lang); ?>: </label><br>
                                <input type="text" name="title" class="form-control m-0 <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php if(isset($title)): echo $title; endif; ?>" />
                            </fieldset>
                            <fieldset>
                                <label class="form-label"><?php Translation::translate('Description', $site_lang); ?>: </label><br>
                                <textarea name="description" class="form-control m-0 <?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if(isset($description)): echo $description; endif; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <label class="form-label"><?php Translation::translate('Body', $site_lang); ?>: </label><br>
                                <textarea name="body" class="form-control m-0 <?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="20"><?php if(isset($body)): echo $body; endif; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <label class="form-label"><?php Translation::translate('Custom Slug', $site_lang); ?>: (<?php Translation::translate('Optional', $site_lang); ?>) </label><br>
                                <input type="text" name="slug" class="form-control m-0 <?php if (isset($errors['slug'])) : ?>input-error<?php endif; ?>" value="<?php if(isset($slug)): echo $slug; endif; ?>" />
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="btn btn-dark"><?php Translation::translate('Save Post', $site_lang); ?></button>
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