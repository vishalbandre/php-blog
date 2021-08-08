<?php
if (empty($_GET['id'])) {
    header('Location: /index.php');
}
?>
<?php include("../config.php") ?>
<?php include("../header.php") ?>
<div id="article">
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
            <?php if (isset($_COOKIE['blog_user']) && $_COOKIE['blog_user'] == $row['user']) : ?>
                <ul class="actions">
                    <li class="edit">
                        <a href="/posts/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                    </li>
                    <li class="delete">
                        <a href="/posts/delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </li>
                </ul>
            <?php endif; ?>
            <div class="post-description">
                <?php echo $row['description']; ?>
            </div>
            <div class="post-body">
                <?php echo $row['body']; ?>
            </div>
        <?php endwhile; ?>
    <?php
    }
    ?>
</div>
<?php include("../sidebar.php") ?>
<?php include("../footer.php") ?>