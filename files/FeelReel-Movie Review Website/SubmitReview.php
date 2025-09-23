<?php
// Start the session to access session variables
session_start();

// Connect to the database
require 'testDatabase.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit a review.");
}
// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve and sanitize form inputs
$movie_id = intval($_POST['movie_id']); 
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];

// Prepare SQL query to insert the review into the database
// 'status' is set to 'pending' by default (likely for admin approval)
$sql = "INSERT INTO reviews (movie_id, user_id, rating, review_text, status)
        VALUES (?, ?, ?, ?, 'pending')";

// Initialize the prepared statement
$stmt = $mysqli->stmt_init();

// Prepare the SQL statement and check for errors
if (! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Bind parameters to the prepared statement
// i = integer, s = string (movie_id, user_id, and rating as integers, review_text as string)
$stmt->bind_param("iiis", $movie_id, $user_id, $rating, $review_text);

// Execute the statement and handle success or error
if ($stmt->execute()) {
    header("Location: Home.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
