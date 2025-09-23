<?php
// Database connection settings
$host = "localhost";
$dbname = "testdb";
$username = "root";
$password = "";

// Create a new MySQLi connection using named parameters
$mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
    // Terminate the script and display the connection error message
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
?>