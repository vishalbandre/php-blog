<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php
    if (empty($_COOKIE['blog_user'])) {
        header('Location: /index.php');
    }
?>
<div id="content">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') :
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
        }

        if ($description == null) {
            $errors['description'] = 'Description is required.';
        }

        if ($body == null) {
            $errors['body'] = 'Article body is required.';
        }

        // echo "<br />We encounted (" . count($errors) . ") errors.<br />";

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <form action="/posts/create.php" method="POST">
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
                    <textarea name="body" id="body" cols="30" rows="20"><?php echo $body; ?></textarea>
                </p>
                <p>
                    <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
                </p>
            </form>
        <?php
            return null;
        }

        $sql = "INSERT INTO posts (title, user, description, body) VALUES('" . $title . "', '" . $user  . "', '" . $description . "', '" . $body . "')";

        // $sql = "INSERT INTO posts (title, user, description, body) VALUES('" . $title . "', '" . $body . "')";

        if ($conn->query($sql) === TRUE) {
            header('Location: /index.php');
        } else {
            echo $conn->error;
            echo "Error";
        }
        $conn->close();
    else : ?>
        <form action="/posts/create.php" method="POST">
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
                <textarea name="body" id="body" cols="30" rows="20"></textarea>
            </p>
            <p>
                <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
            </p>
        </form>
    <?php endif; ?>
    <?php // include("footer.php"); 
    ?>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>