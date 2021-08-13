<?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php
    if (!$_SESSION['logged_in']) {
        header('Location: /index.php');
    }
?>
<main class="content">
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

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/post-create.php") ?>
        <?php
            return null;
        } else {

        $sql = "INSERT INTO posts (title, user, description, body) VALUES('" . $title . "', '" . $user  . "', '" . $description . "', '" . $body . "')";

        if ($conn->query($sql) === TRUE) {
            header('Location: /index.php');
        } else {
            $error = $conn->error;
            ?>
            <p class="unknown-error">Something went wrong. Please try again later.</p>
            <?php
        }
        }
    else : ?>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/post-create.php") ?>
    <?php endif; ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php");
$conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>