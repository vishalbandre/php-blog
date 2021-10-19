<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

<?php
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="content">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['user'])) {
                            $user = htmlspecialchars($_POST['user']);
                        } else {
                            $user = null;
                        }

                        if (!empty($_POST['title'])) {
                            $title = htmlspecialchars($_POST['title']);
                        } else {
                            $title = null;
                        }

                        if (!empty($_POST['body'])) {
                            $body = htmlspecialchars($_POST['body']);
                        } else {
                            $body = null;
                        }

                        if (!empty($_POST['description'])) {
                            $description = htmlspecialchars($_POST['description']);
                        } else {
                            $description = null;
                        }

                        $errors = array();

                        if ($title == null) {
                            $errors['title'] = 'Title is required.';
                        } else {
                            // also check for existing title
                            $check = "SELECT title FROM posts WHERE title='" . $title . "' LIMIT 1";
                            $result = $conn->query($check);
                            if ($result->num_rows > 0) {
                                $errors['title'] = 'Article with this title already exists.';
                            }
                        }

                        if ($description == null) {
                            $errors['description'] = 'Description is required.';
                        }

                        if ($body == null) {
                            $errors['body'] = 'Article body is required.';
                        }

                        if (count($errors) <= 0) {

                            $data = array(
                                'title' => $title,
                                'user' => $user,
                                'description' => $description,
                                'body' => $body
                            );

                            // Create post
                            $post = new Post();
                            $q = $post->insert($data);

                            // If post is created successfully, redirect to homepage.
                            if ($q !== null) {
                                header('Location: /index.php');
                                $_SESSION['message'] = '<div class="alert alert-success">Article saved successfully.</div>';
                                die();
                            } else {
                                // If post creation fails, show warning
                                $_SESSION['message'] = '<div class="alert alert-warning">Failed to save the article.</div>';
                            }
                        }
                    } ?>
                    <?php
                    if (count($errors) > 0) {
                        // Show errors, if there are any in $errors array
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
                        }
                    }
                    ?>
                    <form action="/posts/create.php" method="POST" class="form">
                        <h3 class="form-caption">New Post</h3>
                        <div class="form-inner">
                            <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                            <fieldset>
                                <label class="form-label">Title: </label><br>
                                <input type="text" name="title" class="form-control m-0 <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php echo $title; ?>" />
                            </fieldset>
                            <fieldset>
                                <label class="form-label">Description: </label><br>
                                <textarea name="description" class="form-control m-0 <?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php echo $description; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <label class="form-label">Body: </label><br>
                                <textarea name="body" class="form-control m-0 <?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="20"><?php echo $body; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="btn btn-dark">Save Post</button>
                            </fieldset>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>