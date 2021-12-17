<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Use Language namespace to handle the languages
use Admin\Language;
use Admin\Translation;

// Set language if it is provided else set it to default (en)
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}
?>
<footer class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            &copy; <a href="/"><?php Translation::translate('Colors', $site_lang); ?></a>. <?php Translation::translate('copyrights', $lang); ?>.
        </div>
        <div class="col-md-7">
            <div class="container" style="display: block;">
                <div class="row">
                    <div class="col-md-12">
                        <strong>Browse this site in: </strong>
                        <?php
                        $language = new Language();
                        $results = $language->getAllLanguages();

                        if ($results) {
                            foreach ($results as $result) {
                        ?><?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == $result['prefix']) { ?>
                        <a class="badge rounded-pill bg-dark" href="/<?php echo $result['prefix']; ?>/set-language">
                            <?php
                                    $n = $result["name"];
                                    echo $n;
                            ?>
                        </a>
                    <?php } else { ?>
                        <a href="/<?php echo $result['prefix']; ?>/set-language">
                            <?php
                                    $n = $result["name"];
                                    echo $n;
                            ?>
                        </a>
                    <?php } ?>
            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="back-to-top-wrapper">
                <a href="#" class="back-to-top-link" aria-label="Scroll to Top" onclick="scrollToTop()">🔝</a>
            </div>
        </div>
    </div>
</footer>