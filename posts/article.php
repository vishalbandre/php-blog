<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Use Post namespace to interact with posts table
use Post\Post;

// Use Language namespace to handle the languages
use Admin\Language;

use Admin\Translation;

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
}

if (!empty($_GET['slug'])) {
    $slug = $_GET['slug'];
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <article class="single-post">
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION["message"]);
                    }
                    ?>
                    <?php
                    $post = new Post();
                    if (isset($id)) {
                        $result = $post->get($id);
                    } else {
                        $result = $post->getBySlug($slug);
                    }

                    if ($result->num_rows > 0) {
                    ?>
                        <?php while ($row = $result->fetch_array()) : ?>
                            <?php
                            $language = new Language();
                            $prefix = $language->getPrefixById($row['language_id']);
                            ?>
                            <h2 class="post-title"><?php echo $row['title']; ?></h2>
                            <section class="author">
                                <strong>Posted by: </strong>
                                <a href="/accounts/profile/<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
                                <strong> on:</strong>
                                <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
                                <div class="mt-2 p-2" style="border: solid 1px silver;">
                                    <?php
                                    $language = new Language();
                                    $posts = new Post();

                                    if (isset($_GET['lang']) && $_GET['lang'] == 'en') {
                                        $lang_results = $posts->getAllLanguageVariants($row['id']);
                                    } else {
                                        $lang_results = $posts->getAllLanguageSiblingPosts($row['id']);
                                    }

                                    if ($lang_results) {
                                        echo '<div>';
                                        if ($lang_results->num_rows > 0)
                                            echo 'This article is also available in: ';

                                        foreach ($lang_results as $lang_result) {
                                            $p = $language->getPrefixById($lang_result['language_id']);
                                            $current_language = $language->get($p);
                                            $current_language_result = $current_language->fetch_assoc();
                                            if ($current_language_result['id'] != $row['language_id']) {
                                                if ($lang_result['slug'] != '') {

                                    ?>
                                                    <a href="/<?php echo $p; ?>/posts/view/<?php echo $lang_result['slug']; ?>">
                                                        <?php echo $current_language_result["name"]; ?>
                                                    </a>
                                            <?php }
                                                
                                            }
                                        }
                                        if (isset($_GET['lang']) && $_GET['lang'] != 'en') {
                                            $current_language = $language->getDefault();
                                            $current_language_result = $current_language->fetch_assoc();
                                            $p = $posts->getBasePost($row['id']);
                                            $base_post = $p->fetch_assoc();
                                            ?>
                                            <a href="/<?php echo $current_language_result['prefix']; ?>/posts/view/<?php echo $base_post['slug']; ?>">
                                                <?php echo $current_language_result["name"]; ?>*
                                            </a>
                                    <?php
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </section>
                            <br>
                            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['user']) && $_SESSION['user'] == $row['user'] || isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                                <ul class="actions">
                                    <li>
                                        <a href="/<?php echo $prefix; ?>/posts/edit/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-primary"><?php Translation::translate('Edit', $site_lang); ?></a>
                                    </li>
                                    <li>
                                        <a href="/<?php echo $prefix; ?>/posts/delete/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-danger"><?php Translation::translate('Delete', $site_lang); ?></a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <summary class="post-description">
                                <?php echo $row['description']; ?>
                            </summary>
                            <section class="post-body">
                                <?php echo nl2br($row['body']); ?>
                            </section>
                        <?php endwhile; ?>
                    <?php
                    } else {
                        header('HTTP/1.0 404 Not Found', TRUE, 404);
                        die(header('location: /errors/404.php'));
                    }
                    ?>
                </article>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

</body>

</html>