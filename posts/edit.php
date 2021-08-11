<?php
if (empty($_GET['id']) || empty($_COOKIE['blog_user'])) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="content">
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
            <form action="/posts/edit.php" method="POST">
                <input name="id" type="hidden" value="<?php echo $id; ?>" />
                <input name="user" type="hidden" value="<?php echo $_COOKIE['blog_user']; ?>" />
                <p>
                    <label for="title">Title: </label><br>
                    <input type="text" name="title" id="title" value="<?php echo $title; ?>" />
                </p>
                <p>
                    <label for="description">Description: </label><br>
                    <textarea name="description" id="description" cols="30" rows="10"><?php echo $description; ?></textarea>
                </p>
                <p>
                    <label for="body">Body: </label><br>
                    <textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
                </p>
                <p>
                    <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
                </p>
            </form>
        <?php
            return null;
        }

        $sql = "UPDATE posts SET title='$title', description='$description', body='$body' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            header('Location: /posts/article.php?id=' . $id);
        } else {
            echo $conn->error;
            echo "Error";
        }
        $conn->close();
    else : ?>

        <?php
        $check = "SELECT * FROM posts WHERE id='" . $_GET['id'] . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
        ?>
            <?php foreach ($result as $key => $value) : ?>
                <form action="/posts/edit.php" method="POST">
                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <input name="user" type="hidden" value="<?php echo $value['user']; ?>" />
                    <p>
                        <label for="title">Title: </label><br>
                        <input type="text" name="title" id="title" value="<?php echo $value['title']; ?>" />
                    </p>
                    <p>
                        <label for="description">Description: </label><br>
                        <textarea name="description" id="description" cols="30" rows="10"><?php echo $value['description']; ?></textarea>
                    </p>
                    <p>
                        <label for="body">Body: </label><br>
                        <textarea name="body" id="body" cols="30" rows="10"><?php echo $value['body']; ?></textarea>
                    </p>
                    <p>
                        <button type="submit" name="submit" value="create" class="button button-ok">Update Post</button>
                    </p>
                </form>
            <?php endforeach; ?>
        <?php
        } else {
            $error = "Something went wrong. " . $conn->error;
            echo $conn->error;
        }
        ?>
    <?php endif; ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>