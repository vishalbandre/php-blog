<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/Database.php") ?>
<?php

use Admin\Language;
?>
<?php ob_start(); ?>
<!DOCTYPE html>

<?php
// check if 'lang' cookie is set
if (isset($is_article_view) && $is_article_view) {
    $site_lang = $_GET['lang'];
} else if(isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else if ($isset($lang)) {
    $site_lang = $lang;
} else {
    $site_lang = 'en';
}

$site_lang_obj = new Language();

if ($site_lang_obj->isRTL($site_lang)) {
    echo '<html lang="' . $site_lang . '" dir="rtl">';
} else {
    echo '<html lang="' . $site_lang . '" dir="ltr">';
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PHP Blog</title>
    <link rel="stylesheet" href="/assets/splide/css/splide.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- <link rel="stylesheet" href="/assets/css/rtl/style.css"> -->
</head>

<body>