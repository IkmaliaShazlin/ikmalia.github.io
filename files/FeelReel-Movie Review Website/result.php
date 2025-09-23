<?php
// Connect to database
$mysqli = require __DIR__ . "/MovieFilter.php";

// Capture GET values safely
$year = $_GET['year'] ?? '';
$language = $_GET['language'] ?? '';
$genre = $_GET['genre'] ?? '';
$query = $_GET['query'] ?? '';

// Build the SQL dynamically
$sql = "SELECT * FROM movies WHERE 1=1";

// Filter by year, language, genre, and search query (if provided)
if (!empty($year)) {
    $sql .= " AND year = '$year'";
}
if (!empty($language)) {
    $sql .= " AND language = '$language'";
}
if (!empty($genre)) {
    $sql .= " AND genre = '$genre'";
}
if (!empty($query)) {
    $safeQuery = $mysqli->real_escape_string($query);
    $sql .= " AND (title LIKE '%$safeQuery%' OR synopsis LIKE '%$safeQuery%')";
}

// Execute the query
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filtered Results</title>
    <link rel="stylesheet" href="Home.css">
</head>
<body>
    <h2>Filtered Movie Results</h2>
    <!-- Display movies as cards -->
    <div class="movie-slider">
        <?php while ($movie = $result->fetch_assoc()): ?>
            <div class="movie-card">
                <a href="<?= htmlspecialchars($movie['details_page']) ?>" target="_blank" style="text-decoration: none;">
                    <!-- Display movie poster and title -->
                    <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                    <p><?= htmlspecialchars($movie['title']) ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
