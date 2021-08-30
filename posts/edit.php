<?php
if (!isset($_SESSION)) {
    session_start();
}

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

<main class="container">
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


                $sql = "UPDATE posts SET title=\"$title\", description=\"$description\", body=\"$body\" WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['message'] = '<div class="success">Saved successfully.</div>';
                    header('Location: /posts/article.php?id=' . $id);
                }
            }
        } ?>

        <?php
        if (isset($errors) && count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
        }
        ?>

        <?php
        $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
        ?>
            <?php while ($row = $result->fetch_array()) : ?>
                <form action="" method="POST" class="posts-forms">
                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <input name="user" type="hidden" value="<?php echo $value['user']; ?>" />
                    <h3 class="form-caption">Edit Article</h3>
                    <div class="form-inner">
                        <fieldset>
                            <label for="title">Title: </label><br>
                            <input type="text" name="title" id="title" value="<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                    echo $title;
                                                                                } else {
                                                                                    echo $row['title'];
                                                                                } ?>" />
                        </fieldset>
                        <fieldset>
                            <label for="description">Description: </label><br>
                            <textarea name="description" id="description" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                                    if (isset($description)) : echo $description;
                                                                                                    endif;
                                                                                                } else {
                                                                                                    echo $row['description'];
                                                                                                } ?></textarea>
                        </fieldset>
                        <fieldset>
                            <label for="body">Body: </label><br>
                            <textarea name="body" id="body" cols="30" rows="10"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                                    if (isset($body)) : echo $body;
                                                                                    endif;
                                                                                } else {
                                                                                    echo $row['body'];
                                                                                } ?></textarea>
                        </fieldset>
                        <fieldset>
                            <button type="submit" name="submit" value="create" class="button button-ok">Save Article</button>
                        </fieldset>
                    </div>
                </form>
            <?php endwhile; ?>
        <?php
        }
        ?>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>