<?php

// Connect to the database
$mysqli = require __DIR__ . "/testDatabase.php";

// Prepare the SQL query to check if the email exists in the database
// Uses real_escape_string() to prevent SQL injection by escaping special characters
$sql = sprintf("SELECT * FROM user
                WHERE email = '%s'",
                $mysqli->real_escape_string($_GET["email"]));

// Execute the query
$result = $mysqli->query($sql);

// Check if the email is available (i.e., not found in the database)
$is_available = $result->num_rows === 0;

// Set the header to indicate that the response is in JSON format
header("Content-Type: application/json");

// Return the result as JSON (true if available, false if taken)
echo json_encode(["available" => $is_available]);