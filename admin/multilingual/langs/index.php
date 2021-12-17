<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Use Language namespace to handle the languages
use Admin\Language;

if (!$_SESSION['logged_in'] || !$_SESSION['is_admin']) :
    header('Location: /index.php');
endif;
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
                <ul class="nav nav-tabs mt-5">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/admin/multilingual/langs/">All Languages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/multilingual/langs/create">Add New Language</a>
                    </li>
                </ul>
                <div class="mt-3">
                <?php
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION["message"]);
                }
                ?>
                </div>
                <section>
                    <?php
                    $language = new Language();
                    $results = $language->getAllLanguages();

                    if ($results) {
                    ?>
                        <div class="mt-2">
                            <?php
                            foreach ($results as $result) {
                            ?>
                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        <?php echo $result["name"]; ?><?php if ($result['is_default']) : ?><small><i> (Site Default Language)</i></small><?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-primary" href="/admin/multilingual/language/edit/<?php echo $result['prefix']; ?>">Edit</a>
                                    </div>
                                    <?php if (!$result['is_default']) : ?>
                                        <div class="col-md-2">
                                            <a class="btn btn-danger" href="/admin/multilingual/language/delete/<?php echo $result['prefix']; ?>">Delete</a>
                                        </div>
                                    <?php endif; ?>
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