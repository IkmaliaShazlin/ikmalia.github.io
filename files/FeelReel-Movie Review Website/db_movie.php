<?php
$host = "localhost";
$user = "root";
$pass = ""; // Usually blank for XAMPP
$db = "testdb"; // Replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>