<?php

// Database connection configuration
$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "";

// Create a new MySQLi connection
$mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

// Check if the connection failed and display error message
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Return the connection object to be used in other scripts
return $mysqli;
?>