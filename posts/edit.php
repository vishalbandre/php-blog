<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;

// Validations
use Validator\Validator;

// Use Language namespace to handle the languages
use Admin\Language;

use Admin\Translation;

// String to Slug Conversion
use Util\Util;

if (!isset($_GET['id']) || !$_SESSION['logged_in']) {
    header('Location: /index.php');
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");

$post_id = null;
if (!isset($_GET['id']) || !isset($_SESSION['logged_in']) || !isset($_GET['user'])) {
    header('Location: /index.php');
} else if (isset($_SESSION['is_admin']) || $_SESSION['user'] == $_GET['user']) {
    $user = trim($_GET['user']);
    $id = $_GET['id'];
    $check = "SELECT * FROM posts WHERE user='$user' AND id=$id";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        $post_id = $_GET['id'];
    } else if (isset($_SESSION['is_admin'])) {
        $id = $_GET['id'];
        $check = "SELECT * FROM posts WHERE id=$id";
        $result = $conn->query($check);
        $post_id = $_GET['id'];
    } else {
        header('Location: /index.php');
    }
} else {
    header('Location: /index.php');
}

$posts = new Post();
$rs = $posts->get($post_id);

if(!$rs) {
    header('Location: /index.php');
}

// Initialize language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

$language = new Language();
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

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

                        if (!empty($_POST['slug'])) {
                            $slug = htmlspecialchars($_POST['slug']);
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
                            $errors['title'] = 'Title is required.';
                        } else {
                            // also check for existing title
                            $check = "SELECT title FROM posts WHERE title='" . $title . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if (!is_null($title) && $row['title'] != $title) {
                                        $errors['title'] = Translation::translate('Article with this title already exists', $site_lang, true);
                                    }
                                }
                            }
                        }

                        if ($title != null && $slug == null) {
                            $util = new Util();

                            // Turn title to slug if not slug is provided
                            $slug = $util->stringToSlug($title);
                        } else {
                            $validator = new Validator();
                            $errors_array = $validator->validateSlug($slug);
                            $errors = array_merge($errors, $errors_array);
                        }

                        if ($description == null) {
                            $errors['description'] = Translation::translate('Description is required', $site_lang, true);
                        }

                        if ($body == null) {
                            $errors['body'] = Translation::translate('Article body is required', $site_lang, true);
                        }


                        if (isset($errors) && count($errors) <= 0) {

                            if($lang != 'en') {
                                $slug = $slug . '-' . $lang;
                            }

                            $data = array(
                                'title' => $title,
                                'description' => $description,
                                'body' => $body,
                                'slug' => $slug,
                            );

                            $post = new Post();
                            $q = $post->update_post($data, $id);

                            if ($q !== null) {
                                $_SESSION['message'] = '<div class="alert alert-success">Saved successfully.</div>';
                                header('Location: /');
                            }
                        }
                    } ?>

                    <?php
                    if (isset($errors) && count($errors) > 0) {
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
                        }
                    }
                    ?>
                    <?php
                    if ($result->num_rows > 0) {
                    ?>
                        <?php while ($row = $result->fetch_array()) : ?>
                            <form action="" method="POST" class="form">
                                <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                                <input name="user" type="hidden" value="<?php echo $value['user']; ?>" />
                                <?php
                                $current_language0 = $language->get($lang);
                                $current_language0_result = $current_language0->fetch_assoc();
                                ?>
                                <h3 class="form-caption"><?php Translation::translate('Edit Article', $lang); ?> <?php if (isset($lang) && $lang != 'en') : echo '(' . $current_language0_result['name'] . ')';
                                                                        endif; ?></h3>
                                <div class="form-inner">
                                    <?php
                                    if ($lang != 'en') {
                                        $posts = new Post();
                                        $p = $posts->getBasePost($row['id']);
                                        $base_post = $p->fetch_assoc();
                                    }
                                    ?>
                                    <fieldset>
                                        <label for="title" class="form-label"><?php Translation::translate('Title', $lang); ?>: </label><br>
                                        <input type="text" name="title" class="form-control m-0 <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                                echo $title;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo $row['title'];
                                                                                                                                                                            } ?>" />
                                        <?php if ($lang != 'en') { ?>
                                            <p class="pt-2 hint">
                                                <small><?php Translation::translate('Base Title', $lang); ?>: <?php echo $base_post['title']; ?></small>
                                            </p>
                                        <?php } ?>
                                    </fieldset>
                                    <fieldset>
                                        <label for="description" class="form-label"><?php Translation::translate('Description', $lang); ?>: </label><br>
                                        <textarea name="description" class="form-control m-0 <?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                                                if (isset($description)) : echo $description;
                                                                                                                                                                                                endif;
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo $row['description'];
                                                                                                                                                                                            } ?></textarea>
                                        <?php if ($lang != 'en') { ?>
                                            <p class="pt-2 hint">
                                                <small><?php Translation::translate('Base Description', $lang); ?>: <?php echo $base_post['description']; ?></small>
                                            </p>
                                        <?php } ?>
                                    </fieldset>
                                    <fieldset>
                                        <label for="body" class="form-label"><?php Translation::translate('Body', $lang); ?>: </label><br>
                                        <textarea name="body" class="form-control m-0 <?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                                if (isset($body)) : echo $body;
                                                                                                                                                                                endif;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo $row['body'];
                                                                                                                                                                            } ?></textarea>
                                        <?php if ($lang != 'en') { ?>
                                            <p class="pt-2 hint">
                                                <small>
                                                <?php Translation::translate('Base Body', $lang); ?>: <?php echo $base_post['body']; ?>
                                                </small>
                                            </p>
                                        <?php } ?>
                                    </fieldset>
                                    <?php if ($lang == 'en') : ?>
                                        <fieldset>
                                            <label for="slug" class="form-label"><?php Translation::translate('Custom Slug', $lang); ?>: (<?php Translation::translate('Optional', $lang); ?>) </label><br>
                                            <input type="text" name="slug" class="form-control m-0 <?php if (isset($errors['slug'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                                                                                echo $slug;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo $row['slug'];
                                                                                                                                                                            } ?>" />
                                        </fieldset>
                                    <?php else: ?>
                                        <input type="hidden" name="slug" class="form-control m-0 <?php if (isset($errors['slug'])) : ?>input-error<?php endif; ?>" value="<?php if(isset($base_post['slug'])): echo $base_post['slug']; endif; ?>" />
                                    <?php endif; ?>
                                    <fieldset>
                                        <button type="submit" name="submit" value="create" class="btn btn-dark"><?php Translation::translate('Save Article', $lang); ?></button>
                                    </fieldset>
                                </div>
                            </form>
                        <?php endwhile; ?>
                    <?php
                    }
                    ?>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="display-6 mb-3"><small><?php Translation::translate('Translate', $lang); ?></small></h6>
                        <?php
                        $posts = new Post();

                        if ($_GET['lang'] == 'en') {
                            $results = $posts->getAllLanguageVariants($_GET['id']);
                        } else {
                            $results = $posts->getAllLanguageSiblingPosts($_GET['id']);
                        }

                        $check = "SELECT id, user FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
                        $r = $conn->query($check);

                        $post_data = $r->fetch_assoc();

                        $post_id = $post_data['id'];
                        $post_user = $post_data['user'];

                        if ($results) {
                            echo '<ul class="list-group">';
                            foreach ($results as $result) {
                                $prefix = $language->getPrefixById($result['language_id']);
                                $current_language = $language->get($prefix);
                                $current_language_result = $current_language->fetch_assoc();
                        ?>
                                <a <?php if ($lang == $prefix) : echo 'class="badge rounded-pill bg-dark" style="font-size: 14px; margin-top: 5px; margin-bottom: 5px;"';
                                    endif; ?> href="/<?php echo $prefix; ?>/posts/edit/<?php echo $result['id']; ?>/<?php echo $post_user; ?>">
                                    <?php echo $current_language_result["name"]; ?>
                                </a>
                        <?php }
                            echo '</ul>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>