<?php
session_start();
include 'db_profile.php'; // Or use db_movie.php if it's the same connection

if (!isset($_SESSION['user_id'], $_POST['movie_id'], $_POST['list_type'])) {
    header("Location: profile.php");
    exit();
}

$userId = $_SESSION['user_id'];
$movieId = intval($_POST['movie_id']);
$listType = $_POST['list_type']; // 'favourite' or 'watchlist'

$stmt = $conn->prepare("DELETE FROM user_movie_list WHERE user_id = ? AND movie_id = ? AND list_type = ?");
$stmt->bind_param("iis", $userId, $movieId, $listType);
$stmt->execute();
$stmt->close();

header("Location: profile.php");
exit();
