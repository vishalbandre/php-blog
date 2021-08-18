<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<article class="item">
    <a href="/posts/article.php?id=<?php echo $row['id']; ?>">
        <h2 class="title">
            <?php echo $row["title"]; ?>
        </h2>
    </a>
    <div class="author">
        <strong class="label">Author: </strong>
        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
    </div>
    <summary class="post-description">
        <?php echo $row['description']; ?>
    </summary>
    <?php if ($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']) : ?>
        <ul class="actions">
            <li class="edit">
                <a href="/posts/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
            </li>
            <li class="delete">
                <a href="/posts/delete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </li>
        </ul>
    <?php endif; ?>
</article>