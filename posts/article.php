<?php
session_start();

if (empty($_GET['id'])) {
    header('Location: /index.php');
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/components/header.php") ?>
<main class="single-post">
    <?php
    $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
    ?>
        <?php while ($row = $result->fetch_array()) : ?>
            <h2 class="post-title"><?php echo $row['title']; ?></h2>
            <div class="author">
                <strong>Author: </strong>
                <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
            </div>
            <br>
            <?php if($_SESSION['logged_in'] && $_SESSION['user'] == $row['user'] || $_SESSION['is_admin']): ?>
                <ul class="actions">
                    <li class="edit">
                        <a href="/posts/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                    </li>
                    <li class="delete">
                        <a href="/posts/delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </li>
                </ul>
            <?php endif; ?>
            <summary class="post-description">
                <?php echo $row['description']; ?>
            </summary>
            <article class="post-body">
                <?php echo nl2br($row['body']); ?>
            </article>
        <?php endwhile; ?>
    <?php
    }
    ?>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/components/footer.php") ?>

</body>
</html>