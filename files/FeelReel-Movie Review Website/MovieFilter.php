<?php
$host = 'localhost';        // or 127.0.0.1
$user = 'root';             // default XAMPP username
$password = '';             // default is empty in XAMPP
$dbname = 'project_database';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
