<?php
include 'db_movie.php';

// Get movie ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch movie details
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
$stmt->close();

// If movie not found
if (!$movie) {
    echo "<p style='color: white;'>Movie not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($movie['title']) ?> - Synopsis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body {
        background: linear-gradient(to right bottom, #ff0017, #d10027, #a0002b, #700c27, #41101d, #41111d, #41111e, #41121e, #701329, #a0102e, #d0112c, #ff2121);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: white;
        margin: 0;
        padding: 30px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    }

    .poster {
        float: left;
        margin-right: 30px;
    }

    .poster img {
        width: 300px;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
    }

    .text {
        overflow: hidden;
    }

    h1 {
        font-size: 32px;
        margin-bottom: 10px;
        color: #ff4d4d; 
    }

    p {
        line-height: 1.6;
        margin: 5px 0 15px;
    }

    iframe {
        border-radius: 8px;
        width: 100%;
        max-width: 560px;
        height: 315px;
    }

    .button-form {
        display: inline-block;
        margin-top: 20px;
        margin-right: 10px;
    }

    .button-form input[type="submit"] {
        background-color: #ff4d4d; 
        color: white;
        font-weight: bold;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button-form input[type="submit"]:hover {
        background-color: #e63737; 
        transform: scale(1.05);
    }

    .review-container {
        background-color: rgba(31, 31, 31, 0.85);
        padding: 30px;
        border-radius: 12px;
        margin-top: 50px;
    }

    .review-container h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #ff4d4d;
    }

    .review-container label {
        font-weight: bold;
        font-size: 14px;
        margin-top: 15px;
        display: block;
    }

    .review-container input[type="number"],
    .review-container textarea {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        background-color: #2c2c2c;
        color: white;
        border: none;
        font-size: 14px;
    }

    .review-container input[type="submit"] {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .review-container input[type="submit"]:hover {
        background-color: #e63737;
    }

    a.box {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 20px;
        background-color: #2a2a2a;
        border-radius: 10px;
        color: #ff4d4d;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    a.box:hover {
        background-color: #3a3a3a;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="poster">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
        </div>
        <div class="text">
            <h1><?= htmlspecialchars($movie['title']) ?></h1>

            <p><strong>Director:</strong><br><?= htmlspecialchars($movie['director']) ?></p>

            <p><strong>Cast:</strong><br><?= nl2br(htmlspecialchars($movie['cast'])) ?></p>

            <p><strong>Synopsis:</strong><br><?= nl2br(htmlspecialchars($movie['synopsis'])) ?></p>

            <p><strong>Trailer:</strong></p>
            <iframe src="<?= htmlspecialchars($movie['trailer_link']) ?>" allowfullscreen></iframe>

            <!-- Add to Favourites Button -->
            <form action="AddToListProfile.php" method="POST" class="button-form">
                <input type="hidden" name="movie_id" value="<?= $id ?>">
                <input type="hidden" name="list_type" value="favourite">
                <input type="submit" class="fav" value="Add to Favourites">
            </form>

            <!-- Add to Watchlist Button -->
            <form action="AddToListProfile.php" method="POST" class="button-form">
                <input type="hidden" name="movie_id" value="<?= $id ?>">
                <input type="hidden" name="list_type" value="watchlist">
                <input type="submit" value="Add to Watchlist">
            </form>

            <br><br>

            <!-- View reviews button -->
            <a href="MovieReview.php?movie_id=<?= $id ?>" class="box">View Reviews</a>
        </div>
        
        <!-- Review submission section -->
        <div class="review-container">
            <h2>Submit Your Review</h2>

            <!-- Form to submit a movie review -->
            <form action="SubmitReview.php" method="POST">
                <input type="hidden" name="movie_id" value="<?= $id ?>">

                <!-- Rating input (from 1 to 5 stars) -->
                <label for="rating">Rating (1–5):</label>
                <input type="number" id="rating" name="rating" min="1" max="5" required>

                <!-- Text area for user's written review -->
                <label for="review">Your Review:</label>
                <textarea id="review" name="review_text" required></textarea>

                <input type="submit" value="Submit Review">
            </form>
        </div>
    </div>
</body>
</html>
