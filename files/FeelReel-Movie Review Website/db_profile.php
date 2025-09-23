<?php
$servername = "localhost";     // usually localhost
$username = "root";            // default username in XAMPP
$password = "";                // default password is blank in XAMPP
$database = "testdb";  // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>