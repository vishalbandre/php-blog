<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET['id']) || !$_SESSION['logged_in']) {
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

<main class="content">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') :
        if (!empty($_POST['id'])) {
            $id = htmlspecialchars($_POST['id']);
        } else {
            $id = null;
        }

        if ($_POST['submit'] == 'yes') {
            $sql_delete = "DELETE FROM carousels_images WHERE carousel_id=$id";
            if ($conn->query($sql_delete) === TRUE) {
                $sql = "DELETE FROM carousels WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    header('Location: /carousels/index.php');
                } else {
                    echo $conn->error;
                    echo "Error";
                }
            }
            $conn->close();
        } else {
            header('Location: /carousels/view.php?id=' . $id);
            // echo "Yes: " . $_POST['submit'];
        }
    else : ?>

        <?php
        $check = "SELECT * FROM carousels WHERE id='" . $_GET['id'] . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
        ?>
            <p>Are you sure to delete this article?</p>
            <?php foreach ($result as $key => $value) : ?>
                <form action="/carousels/delete.php" method="POST">
                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                    <p>
                        <button type="submit" name="submit" value="yes" class="button button-ok">Yes</button>
                        <button type="submit" name="submit" value="no" class="button button-ok">No</button>
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
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>