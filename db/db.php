<?php
$host = "hayabusa.proxy.rlwy.net";
$user = "root";
$password = "pNroptClxDLVNQKbqPXjuEZkcaIdFhVN";
$database = "railway";
$port = 29683;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed (" . $conn->connect_errno . "): " . $conn->connect_error);
}
?>

