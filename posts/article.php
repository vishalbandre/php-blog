<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;

// if (empty($_GET['id']) || empty($_GET['slug'])) {
//     header('Location: /index.php');
// } else {
//     $id = $_GET['id'];
// }

if(!empty($_GET['id'])) {
    $id = $_GET['id'];
}

if (!empty($_GET['slug'])) {
    $slug = $_GET['slug'];
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
                    if ($_SESSION['message']) {
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
                                    <li>
                                        <a href="/posts/edit/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-primary">Edit</a>
                                    </li>
                                    <li>
                                        <a href="/posts/delete/<?php echo $row['id']; ?>/<?php echo $row['user']; ?>" class="btn btn-outline-danger">Delete</a>
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