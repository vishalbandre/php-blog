<?php
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
    <a href="/carousels/categories/view.php?id=<?php echo $row['id']; ?>">
        <h2 class="title">
            <?php echo $row["name"]; ?>
        </h2>
    </a>
    <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']) : ?>
        <ul class="actions">
            <li class="edit">
                <a href="/carousels/categories/edit.php?id=<?php echo $row['id']; ?>">Edit Category</a>
            </li>
            <li class="delete">
                <a href="/carousels/categories/delete.php?id=<?php echo $row['id']; ?>">Delete Category</a>
            </li>
        </ul>
    <?php endif; ?>
</article>