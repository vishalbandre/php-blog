<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Use Language namespace to handle the languages
use Admin\Language;
use Admin\Translation;

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}
?>
<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <h3 class="mt-3">
                    <a href="/admin"><?php Translation::translate('Admin Dashboard', $site_lang); ?></a>
                </h3>
            </div>
            <div class="sidebar-body">
                <div class="accordion" id="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <?php Translation::translate('Multilingual', $site_lang); ?>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <div class="d-flex align-items-start">
                                    <div class="nav flex-column nav-pills me-3" role="tablist" aria-orientation="vertical">
                                        <a href="/admin/multilingual/translations/" class="nav-link"><?php Translation::translate('UI/String Translations', $site_lang); ?></a>
                                        <a href="/admin/multilingual/langs/" class="nav-link"><?php Translation::translate('Languages', $site_lang); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>