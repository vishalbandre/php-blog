<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Testing with random data
// $mem = new Memcached();
// $mem->addServer('127.0.0.1', 11211);
// if ($mem->add("success", "Memcached Success!", 600)) {
//     echo  'Cached!';
// } else {
//     echo 'Data: ' . $mem->get("success");
// }
// echo 'Data: ' . $mem->get("success");

// Testing for MySQL Server
$database = "php_blog";
$servername = "localhost";
$username = "php_blog_user";
$password = "php_blog_password";
$memtest = new Memcached();
$memtest->addServer("127.0.0.1", 11211); // 11211 is a default memcached port
$conn = new mysqli($servername, $username, $password, $database);
$sql = "SELECT * FROM languages";
$retval = $conn->query($sql);

$data = array();
while($row = $retval->fetch_assoc()) {
    $data[] = $row;
}

$sqlkey = "KEY" . md5($sql);
$memtest->set($sqlkey, $data);
$langs = $memtest->get($sqlkey);

foreach($langs as $lang) {
    echo $lang['name'];
}