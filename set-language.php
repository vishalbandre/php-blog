<?php
// Set user interface language, if it is provided else set it to default (en)
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

// check if lang cookie exists, if yes, unset
if (isset($_COOKIE['lang'])) {
    unset($_COOKIE['lang']);
}

setcookie('lang', $lang, time() + (3600 * 24 * 30), '/'); // 30 days

header('Location: ' . $_SERVER['HTTP_REFERER']);