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

// Set language if it is provided else set it to default (en)
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

// Get the language id based on the language code/prefix
$language = new Language();
$lang_id = $language->getIdByPrefix($lang);

// check cookie for lang
if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
}

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}

$language = new Language();
$site_lang_id = $language->getIdByPrefix($site_lang);
?>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="feed">
                    <?php
                    $q = trim($_GET['q']);
                    ?>
                    <h3 class="caption"><?php Translation::translate('Search results for', $site_lang); ?>: <?php echo $q; ?></h3>

                    <?php
                    $post = new Post();
                    $result = $post->search($q, $site_lang_id);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_array()) {
                            if (isset($row['title']) && $row['title'] != '')
                                require($_SERVER['DOCUMENT_ROOT'] . "/posts/item.php");
                        }
                    } else {
                        Translation::translate('Nothing found for this term', $site_lang);
                    }
                    ?>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>