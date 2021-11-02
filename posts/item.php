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
?>

<article class="item">
    <?php if (empty($row['slug'])) { ?>
        <a href="/posts/article.php?id=<?php echo $row['id']; ?>">
            <h2 class="title">
                <?php echo $row["title"]; ?>
            </h2>
        </a>
    <?php } else { ?>
        <a href="/posts/view/<?php echo $row['slug']; ?>">
            <h2 class="title">
                <?php echo $row["title"]; ?>
            </h2>
        </a>
    <?php } ?>
    <section class="author">
        <strong>Posted by: </strong>
        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
        <strong> on:</strong>
        <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
    </section>
    <summary class="post-description">
        <?php echo $row['description']; ?>
    </summary>
    <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']) : ?>
        <ul class="actions">
            <li>
                <a href="/posts/edit/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-primary">Edit</a>
            </li>
            <li>
                <a href="/posts/delete/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-danger">Delete</a>
            </li>
        </ul>
    <?php endif; ?>
</article>