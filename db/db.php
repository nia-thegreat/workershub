<?php
$host = "sql310.infinityfree.com";
$user = "if0_42346957";
$password = "IBS9YhxZERznV";
$database = "if0_42346957_workershub";

$conn = new mysqli($host, $user, $password, $database );

if ($conn->connect_error) {
    die("Connection failed (" . $conn->connect_errno . "): " . $conn->connect_error);
}
?>

