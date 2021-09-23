<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;

if (empty($_GET['id'])) {
    header('Location: /index.php');
} else {
    $id = $_GET['id'];
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

<main class="container">
    <div class="content-area">
        <article class="single-post">
            <?php
            if ($_SESSION['message']) {
                echo $_SESSION['message'];
                unset($_SESSION["message"]);
            }
            ?>
            <?php
            $post = new Post();
            $result = $post->get($id);

            if ($result->num_rows > 0) {
            ?>
                <?php while ($row = $result->fetch_array()) : ?>
                    <h2 class="post-title"><?php echo $row['title']; ?></h2>
                    <section class="author">
                        <strong>Posted by: </strong>
                        <a href="/accounts/view.php?user=<?php echo $row['user']; ?>"><?php echo $row['user']; ?></a>
                        <strong> on:</strong>
                        <?php echo date("l, M j, Y", strtotime($row['created_at'])); ?>
                    </section>
                    <br>
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
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

</body>

</html>