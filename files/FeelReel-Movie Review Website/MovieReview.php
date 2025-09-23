<?php
require 'testDatabase.php'; // Database connection

// Check database connection
if (!$mysqli) {
    die("Database connection failed.");
}

// Get movie ID from URL safely
$movie_id = $_GET['movie_id'] ?? null;
if (!$movie_id) {
    die("Movie ID not provided.");
}

// Fetch movie details
$stmt = $mysqli->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie_result = $stmt->get_result();
$movie = $movie_result->fetch_assoc();
$stmt->close();

if (!$movie) {
    die("Movie not found.");
}

// Fetch reviews for this movie
$sql = "SELECT r.review_text, r.rating, u.username AS reviewer_name, up.profile_picture
        FROM reviews r
        JOIN user u ON r.user_id = u.id
        LEFT JOIN userprofile up ON u.id = up.id
        WHERE r.movie_id = ? AND r.status = 'approved'";
        
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($movie['title']) ?> Reviews</title>
  <style>
    body, html {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #fff;
}

.page-wrapper {
  background-size: cover;
  background-position: center;
  min-height: 100vh;
  position: relative;
}

.overlay {
  background: rgba(0, 0, 0, 0.75);
  min-height: 100vh;
  padding: 40px 20px;
}

.reviews-container {
  max-width: 800px;
  margin: auto;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 10px;
  padding: 30px;
}

.section-title {
  text-align: center;
  font-size: 2em;
  margin-bottom: 20px;
  color:rgb(255, 0, 0);
}

.poster-container {
  text-align: center;
  margin-bottom: 30px;
}

.poster-container img {
  max-width: 200px;
  border-radius: 10px;
}

.review-card {
  background: rgba(255, 255, 255, 0.08);
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 15px;
}

.reviewer-header {
  display: flex;
  align-items: center;
  gap: 10px;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.reviewer-name {
  font-weight: bold;
}

.rating {
  margin: 10px 0;
  color: #ffdd57;
  font-size: 1.2em;
}

.review-text {
  font-style: italic;
}

.no-reviews {
  text-align: center;
  color: #ccc;
  font-style: italic;
}

  </style>
</head>
<!-- Background image set to movie poster -->
<body>
  <div class="page-wrapper" style="background-image: url('<?= htmlspecialchars($movie['poster']) ?>');">
    <div class="overlay">
      <section class="reviews-container">
        <!-- Movie Title -->
        <h2 class="section-title">Movie Reviews: <?= htmlspecialchars($movie['title']) ?></h2>

        <!-- Movie Poster -->
        <div class="poster-container">
          <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Movie Poster">
        </div>

        <!-- Review List -->
        <?php if ($result->num_rows === 0): ?>
          <!-- No Reviews Message -->
          <p class="no-reviews">No reviews for this movie yet.</p>
        <?php else: ?>
          <!-- Loop through reviews -->
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="review-card">
              <div class="reviewer-header">
                <?php
                // Use default profile picture if none exists
                $profilePicPath = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'image/defaultpfp.jpg';
                ?>
                <img src="<?= $profilePicPath ?>" alt="Avatar" class="avatar">
                
                <span class="reviewer-name"><?= htmlspecialchars($row['reviewer_name']) ?></span>
              </div>
              <!-- Star Rating -->
              <div class="rating">
                <?= str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) ?>
              </div>
              <!-- Review Text -->
              <p class="review-text"><?= htmlspecialchars($row['review_text']) ?></p>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </section>
    </div>
  </div>
</body>
</html>
