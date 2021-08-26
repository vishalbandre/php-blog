<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php
if (!$_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<main class="container">
    <section class="content">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload']) && !empty($_FILES["file"]["name"])) :
            if (!empty($_POST['user'])) {
                $user = htmlspecialchars($_POST['user']);
            } else {
                $user = null;
            }

            if (!empty($_POST['caption'])) {
                $caption = htmlspecialchars($_POST['caption']);
            } else {
                $caption = null;
            }

            if (!empty($_POST['file'])) {
                $file = htmlspecialchars($_POST['file']);
            } else {
                $file = null;
            }

            $errors = array();

            if ($caption == null) {
                $errors['caption'] = 'Caption is required.';
            }

            if (empty($_FILES["file"]["name"])) {
                $errors['file'] = 'Image is required.';
            }

            if (count($errors) <= 0) {
                // File upload path
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/images/";
                $fileName = basename($_FILES["file"]["name"]);
                $fileNameNoExtension = preg_replace("/\.[^.]+$/", "", $fileName);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = md5(time()) . "." . $fileType;
                $targetFilePath = $uploadDir . $fileName;

                $fileTypes = array('jpg', 'png', 'jpeg');
                if (in_array($fileType, $fileTypes)) {
                    if (is_dir($uploadDir) && is_writable($uploadDir)) {
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                            $sql = "INSERT INTO images (user, imgpath, caption) VALUES('" . $user . "', '" . $fileName  . "', '" . $caption . "')";

                            if ($conn->query($sql) === TRUE) {
                                $_SESSION['message'] = '<div class="success">Image Uploaded Successfully!</div>';
                                $ref = $_POST['prev'];
                                header("Location: $ref");
                            } else {
                                $_SESSION['message'] = '<div class="warning">Image Upload Failed!</div>';
                            }
                        } else {
                            $_SESSION['message'] = '<div class="warning">Something went wrong, unable to upload this file!</div>';
                        }
                    }
                } else {
                    $_SESSION['message'] = '<div class="warning">Image Type Not Supported!</div>';
                }
            }
        endif;
        ?>

        <?php
        if ($_SESSION['message']) {
            echo $_SESSION['message'];
            unset($_SESSION["message"]);
        }
        ?>

        <?php
        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
        }
        ?>

        <!-- File Upload Form -->
        <form action="" method="POST" class="accounts-forms carousels-forms" enctype="multipart/form-data">
            <h3 class="form-caption">Add New Image</h3>
            <div class="form-inner">
                <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                <input type="hidden" name="prev" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
                <fieldset>
                    <input type="file" name="file" required>
                </fieldset>
                <fieldset>
                    <label>Caption: </label><br>
                    <input type="text" name="caption" class="<?php if (isset($errors['caption'])) : ?>input-error<?php endif; ?>" value="<?php echo $caption; ?>" />
                </fieldset>
                <fieldset>
                    <button type="submit" name="upload" value="upload-image" class="button button-ok">Upload Image</button>
                </fieldset>
            </div>
        </form>

    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>

<?php $conn->close();
include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>