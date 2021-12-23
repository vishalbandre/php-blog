<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Language namespace to handle the languages
use Admin\Language;
use Admin\Term;
use Admin\Translation;

if (!isset($_GET['id']) || !$_SESSION['is_admin']) {
    // If language is not present or authenticated user is not an admin one redirect
    // them to homepage.
    header('Location: /');
} else {
    $id = $_GET['id'];
    $t = new Term();
    $term_results = $t->get($id);
    if ($term_results) {
        $term = $term_results->fetch_assoc();
    } else {
        header('HTTP/1.0 404 Not Found', TRUE, 404);
        die(header('location: /errors/404.php'));
    }
}

?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar-admin.php") ?>
        </div>
        <div class="col-md-9">
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
                        $deleted = $t->delete($id);

                        if ($deleted) {
                            header('Location: /admin/multilingual/translations/');
                            $_SESSION['message'] = '<div class="alert alert-success">Term deleted successfully.</div>';
                            die();
                        }
                        $conn->close();
                    } else {
                        header('Location: /admin/multilingual/translations/');
                        die();
                    }
                else : ?>
                    <div class="container mt-4 p-0">
                        <div class="row">
                            <div class="col">
                                <h5 class="mt-3">Are you sure you want to delete term "<?php echo $term['term']; ?>" and related translations</h5>
                                <form action="" method="POST">
                                    <input name="id" type="hidden" value="<?php echo $term['id']; ?>" />
                                    <p>
                                        <button type="submit" name="submit" value="yes" class="btn btn-danger btn-sm">Yes</button>
                                        <button type="submit" name="submit" value="no" class="btn btn-outline-secondary btn-sm">No</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>