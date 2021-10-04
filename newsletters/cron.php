<?php

use Newsletter\Newsletter;

// Autoload required classes
require_once(dirname(__DIR__) . "/vendor/autoload.php");

// Create newsletter instance
$newsletter = new Newsletter();

// Send emails and return list of emails to store the logs
$emails = $newsletter->send();

/**
 * Email Newsletter Information Log
 * Content:
 * 1) Timestamp
 * 2) Email addresses
 */

date_default_timezone_set("Asia/Calcutta");
echo PHP_EOL.PHP_EOL;
echo 'Newsletter generated on: ' . $date = date('Y/m/d H:i:s');
echo PHP_EOL;
echo '------------------------------------------------------------------------------';
echo PHP_EOL;
echo 'Subscribers: '.PHP_EOL;
echo '-----------------------'.PHP_EOL;

// Output emails to store in log file located at /home/vish/dev/web/php/blog/logs/newsletters.txt
foreach ($emails as $email) {
    echo $email . PHP_EOL;
}

echo '------------------------------------------------------------------------------'.PHP_EOL.PHP_EOL;
echo '********************************************************************************'.PHP_EOL;