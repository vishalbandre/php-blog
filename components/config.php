<?php

$server = 'localhost';
$username = 'php_blog_user';
$password = 'php_blog_password';
$db = 'php_blog';

// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

// $conn = new mysqli($server, $username, $password, $db);


// $url = parse_url("mysql://b163fa55b0d8b4:85afc349@us-cdbr-east-04.cleardb.com/heroku_cabf8a48af4b019?reconnect=true");

// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $servername = "localhost";
// $username = "php_blog_user";
// $password = "php_blog_password";

// // Create connection
// $conn = new mysqli($servername, $username, $password);

// echo "Connecting";

// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";
?>