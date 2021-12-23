<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Term namespace to handle the translations
use Admin\Term;

// Use Translation namespace to handle the translations
use Admin\Translation;

// Use Language namespace to handle the languages
use Admin\Language;

if (!$_SESSION['logged_in'] || !$_SESSION['is_admin']) :
    header('Location: /index.php');
endif;

// Set language if it is provided else set it to default (en)
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
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
                <h6 class="display-6 mt-3">UI/String Translations</h6>
                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/admin/multilingual/translations/">All Terms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/multilingual/translations/create-term">Add New Term</a>
                    </li>
                </ul>
                <section>
                    <?php
                    $term = new Term();
                    $results = $term->getAll();

                    if ($results) {
                    ?>
                        <div class="mt-2">
                            <?php
                            foreach ($results as $result) {
                            ?>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <?php echo $result["term"]; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="btn btn-success btn-sm" href="/admin/multilingual/translations/view-term/<?php echo $result["id"]; ?>">Translations</a> |
                                        <a class="btn btn-primary btn-sm" href="/admin/multilingual/translations/edit-term/<?php echo $result["id"]; ?>">Edit</a> |
                                        <a class="btn btn-danger btn-sm" href="/admin/multilingual/translations/delete-term/<?php echo $result["id"]; ?>">Delete</a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>