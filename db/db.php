<?php

$host = "YOUR_DB_HOST";
$user = "YOUR_DB_USER";
$password = "YOUR_DB_PASSWORD";
$database = "YOUR_DB_NAME";

$host = "YOUR_DB_HOST";
$user = "YOUR_DB_USER";
$password = "YOUR_DB_PASSWORD";
$database = "YOUR_DB_NAME";

$conn = new mysqli($host, $user, $password, $database );

if ($conn->connect_error) {
    die("Connection failed (" . $conn->connect_errno . "): " . $conn->connect_error);
}
?>

