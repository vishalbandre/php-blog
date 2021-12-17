<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Language namespace to handle the languages
use Admin\Language;

if (!isset($_GET['lang']) || !$_SESSION['is_admin']) {
    // If language is not present or authenticated user is not an admin one redirect
    // them to homepage.
    header('Location: /');
} else {
    $_prefix = $_GET['lang'];
    $language = new Language();
    $language_id = $language->getIdByPrefix($_prefix);
    if(!$language_id) {
        header('Location: /admin/multilingual/langs/');
        die();
    }
}

if (isset($_GET['lang'])) {
    $language_prefix = $_GET['lang'];
} else {
    $language_prefix = 'en';
}

if($language_prefix == 'en') {
    header('Location: /');
}

$language = new Language();
$lang_id = $language->getIdByPrefix($language_prefix);

?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') :
                    if (!empty($_POST['id'])) {
                        $id = htmlspecialchars($_POST['id']);
                    } else {
                        $id = null;
                    }
 
                    // If user clicks yes on the confirmation form, proceed with the delete operation.
                    if ($_POST['submit'] == 'yes') {
                        $deleted = $language->delete($id);
                        if ($deleted) {
                            header('Location: /admin/multilingual/langs/');
                            $_SESSION['message'] = '<div class="alert alert-success">Language deleted successfully.</div>';
                            die();
                        }
                        $conn->close();
                    } else {
                        header('Location: /admin/multilingual/langs/');
                    }
                else : ?>

                    <?php
                    $result = $language->get($_prefix);
                    if ($result->num_rows > 0) {
                    ?>
                        <p>Are you sure to delete this language?</p>
                        <?php foreach ($result as $key => $value) : ?>
                            <form action="" method="POST">
                                <input name="id" type="hidden" value="<?php echo $value['id']; ?>" />
                                <p>
                                    <button type="submit" name="submit" value="yes" class="btn btn-danger">Yes</button>
                                    <button type="submit" name="submit" value="no" class="btn btn-outline-secondary">No</button>
                                </p>
                            </form>
                        <?php endforeach; ?>
                    <?php
                    } else {
                        header('HTTP/1.0 404 Not Found', TRUE, 404);
                        die(header('location: /errors/404.php'));
                    }
                    ?>
                <?php endif; ?>
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