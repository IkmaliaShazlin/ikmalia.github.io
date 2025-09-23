<?php
$host = "localhost";
$dbname = "testdb";   // your database name
$username = "root";   // default XAMPP user
$password = "";       // default XAMPP password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
