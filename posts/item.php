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
            <li class="edit">
                <a href="/posts/edit.php?id=<?php echo $row['id']; ?>&user=<?php echo $row['user']; ?>">Edit</a>
            </li>
            <li class="delete">
                <a href="/posts/delete.php?id=<?php echo $row['id']; ?>&user=<?php echo $row['user']; ?>">Delete</a>
            </li>
        </ul>
    <?php endif; ?>
</article>