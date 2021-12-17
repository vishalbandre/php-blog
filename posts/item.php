<?php

/** It will ensure that, this file can only be included as template.
 * If user tries to access it from browser it will redirect to forbidden page.
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die(header('location: /errors/forbidden.php'));
}
?>

<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use Language namespace to handle the languages
use Admin\Language;

use Admin\Translation;

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}
?>

<article class="item">
    <?php
    $language = new Language();
    $prefix = $language->getPrefixById($row['language_id']);
    ?>
    <?php if (empty($row['slug'])) { ?>
        <a href="/<?php echo $prefix; ?>/posts/article.php?id=<?php echo $row['id']; ?>">
            <h2 class="title">
                <?php echo $row["title"]; ?>
            </h2>
        </a>
    <?php } else { ?>

        <a href="/<?php echo $prefix; ?>/posts/view/<?php echo $row['slug']; ?>">
            <h2 class="title">
                <?php echo $row["title"]; ?>
            </h2>
        </a>
    <?php } ?>
    <section class="author">
        <strong>Posted by: </strong>
        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
        <strong> <?php Translation::translate('on', $lang); ?>:</strong>
        <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
    </section>
    <summary class="post-description">
        <?php echo $row['description']; ?>
    </summary>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
        <ul class="actions">
            <li>
                <a href="/<?php echo $prefix; ?>/posts/edit/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-primary"><?php Translation::translate('Edit', $site_lang); ?></a>
            </li>
            <li>
                <a href="/<?php echo $prefix; ?>/posts/delete/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-danger"><?php Translation::translate('Delete', $site_lang); ?></a>
            </li>
        </ul>
    <?php endif; ?>
</article>