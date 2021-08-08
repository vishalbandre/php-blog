<?php

$server = 'localhost';
$username = 'php_blog_user';
$password = 'php_blog_password';
$db = 'php_blog';

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