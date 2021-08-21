<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_GET['id']) || !$_SESSION['logged_in']) {
    header('Location: /index.php');
}

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container">
    <section class="content">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') :
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

            if (count($errors) > 0) {
                foreach ($errors as $key => $value) {
                    echo '<div class="form-error">' . $value . '</div>';
                }
        ?>
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/post-edit.php") ?>
            <?php
            } else {

                $sql = "UPDATE posts SET title=\"$title\", description=\"$description\", body=\"$body\" WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['message'] = '<div class="success">Saved successfully.</div>';
                    header('Location: /posts/article.php?id=' . $id);
                } else {
                    $error = $conn->error;
                }
            }
        else : ?>

            <?php
            $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
            $result = $conn->query($check);
            if ($result->num_rows > 0) {
            ?>
                <?php foreach ($result as $key => $value) : ?>
                    <form action="" method="POST" class="posts-forms">
                        <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                        <input name="user" type="hidden" value="<?php echo $value['user']; ?>" />
                        <h3 class="form-caption">Edit Article</h3>
                        <div class="form-inner">
                            <fieldset>
                                <label for="title">Title: </label><br>
                                <input type="text" name="title" id="title" value="<?php echo $value['title']; ?>" />
                            </fieldset>
                            <fieldset>
                                <label for="description">Description: </label><br>
                                <textarea name="description" id="description" cols="30" rows="10"><?php echo $value['description']; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <label for="body">Body: </label><br>
                                <textarea name="body" id="body" cols="30" rows="10"><?php echo $value['body']; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="create" class="button button-ok">Update Post</button>
                            </fieldset>
                        </div>
                    </form>
                <?php endforeach; ?>
            <?php
            } else {
                $error = "Something went wrong. " . $conn->error;
                echo $conn->error;
            }
            ?>
        <?php endif; ?>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>