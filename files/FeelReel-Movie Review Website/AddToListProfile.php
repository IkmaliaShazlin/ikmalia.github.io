<?php
session_start();
include 'db_movie.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$movieId = intval($_POST['movie_id'] ?? 0);
$listType = $_POST['list_type'] ?? '';

// Only proceed if movie ID is valid and list type is either 'favourite' or 'watchlist'
if ($movieId > 0 && in_array($listType, ['favourite', 'watchlist'])) {

    // Check if the movie is already added to the user's list to prevent duplicates
    $checkStmt = $conn->prepare("SELECT * FROM user_movie_list WHERE user_id = ? AND movie_id = ? AND list_type = ?");
    $checkStmt->bind_param("iis", $userId, $movieId, $listType);
    $checkStmt->execute();
    $exists = $checkStmt->get_result()->num_rows > 0;
    $checkStmt->close();

    // If not already added, insert the movie into the user's list
    if (!$exists) {
        $stmt = $conn->prepare("INSERT INTO user_movie_list (user_id, movie_id, list_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userId, $movieId, $listType);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back to the movie details page
header("Location: Synopsis.php?id=$movieId");
exit();
?>
