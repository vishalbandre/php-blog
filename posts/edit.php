<?php
if (!isset($_SESSION)) {
    session_start();
}

// Use Post namespace to interact with posts table
use Post\Post;

if (!isset($_GET['id']) || !$_SESSION['logged_in']) {
    header('Location: /index.php');
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php");

$post_id = null;
if (!isset($_GET['id']) || !isset($_SESSION['logged_in']) || !isset($_GET['user'])) {
    header('Location: /index.php');
} else if (isset($_SESSION['is_admin']) || $_SESSION['user'] == $_GET['user']) {
    $user = trim($_GET['user']);
    $id = $_GET['id'];
    $check = "SELECT * FROM posts WHERE user='$user' AND id=$id";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        $post_id = $_GET['id'];
    } else if (isset($_SESSION['is_admin'])) {
        $id = $_GET['id'];
        $check = "SELECT * FROM posts WHERE id=$id";
        $result = $conn->query($check);
        $post_id = $_GET['id'];
    } else {
        header('Location: /index.php');
    }
} else {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/posts/post.php") ?>

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

                        if (!empty($_POST['id'])) {
                            $id = htmlspecialchars($_POST['id']);
                        } else {
                            $id = null;
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
                        }

                        if ($description == null) {
                            $errors['description'] = 'Description is required.';
                        }

                        if ($body == null) {
                            $errors['body'] = 'Article body is required.';
                        }

                        if (isset($errors) && count($errors) <= 0) {

                            $data = array(
                                'title' => $title,
                                'description' => $description,
                                'body' => $body
                            );

                            $post = new Post();
                            $q = $post->update_post($data, $id);

                            if ($q !== null) {
                                $_SESSION['message'] = '<div class="alert alert-success">Saved successfully.</div>';
                                header('Location: /posts/article.php?id=' . $id);
                            }
                        }
                    } ?>

                    <?php
                    if (isset($errors) && count($errors) > 0) {
                        foreach ($errors as $key => $value) {
                            echo '<div class="alert alert-danger">' . $value . '</div>';
                        }
                    }
                    ?>
                    <?php
                    $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
                    $result = $conn->query($check);
                    if ($result->num_rows > 0) {
                    ?>
                        <?php while ($row = $result->fetch_array()) : ?>
                            <form action="" method="POST" class="form">
                                <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                                <input name="user" type="hidden" value="<?php echo $value['user']; ?>" />
                                <h3 class="form-caption">Edit Article</h3>
                                <div class="form-inner">
                                    <fieldset>
                                        <label for="title" class="form-label">Title: </label><br>
                                        <input type="text" name="title" class="form-control m-0 <?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                echo $title;
                                                                                            } else {
                                                                                                echo $row['title'];
                                                                                            } ?>" />
                                    </fieldset>
                                    <fieldset>
                                        <label for="description" class="form-label">Description: </label><br>
                                        <textarea name="description" class="form-control m-0 <?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                                if (isset($description)) : echo $description;
                                                                                                                endif;
                                                                                                            } else {
                                                                                                                echo $row['description'];
                                                                                                            } ?></textarea>
                                    </fieldset>
                                    <fieldset>
                                        <label for="body" class="form-label">Body: </label><br>
                                        <textarea name="body" class="form-control m-0 <?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                if (isset($body)) : echo $body;
                                                                                                endif;
                                                                                            } else {
                                                                                                echo $row['body'];
                                                                                            } ?></textarea>
                                    </fieldset>
                                    <fieldset>
                                        <button type="submit" name="submit" value="create" class="btn btn-dark">Save Article</button>
                                    </fieldset>
                                </div>
                            </form>
                        <?php endwhile; ?>
                    <?php
                    }
                    ?>
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