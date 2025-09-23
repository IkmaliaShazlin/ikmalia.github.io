<?php
// Database connection
include 'db_movie.php';

// Check if movie ID is provided in URL
if (!isset($_GET['id'])) {
    echo "No movie ID provided!";
    exit;
}

// Sanitize movie ID
$id = intval($_GET['id']);

// Fetch existing movie
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
$stmt->close();

if (!$movie) {
    echo "Movie not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];
    $release = $_POST['release_year'];
    $director = $_POST['director'];
    $cast = $_POST['cast'];
    $language = $_POST['language'];
    $genre = $_POST['genre'];
    $trailer = $_POST['trailer'];

    // Convert YouTube link to embeddable format
    if (strpos($trailer, 'youtu.be/') !== false) {
        $videoId = substr(parse_url($trailer, PHP_URL_PATH), 1);
        $trailer = "https://www.youtube.com/embed/" . $videoId;
    }
    $trailer = str_replace("watch?v=", "embed/", $trailer);

    // Check if a new poster was uploaded
    if (!empty($_FILES['poster']['name'])) {
        $fileName = basename($_FILES['poster']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if not exists
        }

        move_uploaded_file($_FILES['poster']['tmp_name'], $targetFile);
        $posterPath = $targetFile;

        // Update movie with new poster
        $update = $conn->prepare("UPDATE movies SET title=?, synopsis=?, release_year=?, director=?, cast=?, language=?, genre=?, trailer_link=?, poster=? WHERE id=?");
        $update->bind_param("ssissssssi", $title, $synopsis, $release, $director, $cast, $language, $genre, $trailer, $posterPath, $id);
    } else {
        // Update movie without changing poster
        $update = $conn->prepare("UPDATE movies SET title=?, synopsis=?, release_year=?, director=?, cast=?, language=?, genre=?, trailer_link=? WHERE id=?");
        $update->bind_param("ssisssssi", $title, $synopsis, $release, $director, $cast, $language, $genre, $trailer, $id);
    }

    $update->execute();
    $update->close();

    header("Location: MovieManage.php"); // Redirect after update
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
    
    <style>
        body {
            background-color: #111;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background-color: #222;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }

        h1 {
            text-align: center;
            color: #f9d342;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #ccc;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 5px;
            color: white;
        }

        textarea {
            height: 100px;
        }

        input[type="file"] {
            margin-top: 10px;
            color: #ccc;
        }

        button {
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #f9d342;
            color: #111;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
        }

        button:hover {
            background-color: #e5c233;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #aaa;
            text-decoration: none;
        }

        a.back-link:hover {
            color: #f9d342;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Movie: <?= htmlspecialchars($movie['title']) ?></h1>

        <!-- Form for editing existing movie details -->
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required>

            <label>Synopsis:</label>
            <textarea name="synopsis" required><?= htmlspecialchars($movie['synopsis']) ?></textarea>

            <label>Release Year:</label>
            <input type="number" name="release_year" value="<?= $movie['release_year'] ?>" required>

            <label>Director:</label>
            <input type="text" name="director" value="<?= htmlspecialchars($movie['director']) ?>" required>

            <label>Main Cast:</label>
            <input type="text" name="cast" value="<?= htmlspecialchars($movie['cast']) ?>" required>

            <label>Language:</label>
            <input type="text" name="language" value="<?= htmlspecialchars($movie['language']) ?>" required>

            <label>Genre:</label>
            <input type="text" name="genre" value="<?= htmlspecialchars($movie['genre']) ?>" required>

            <label>Trailer Link:</label>
            <input type="url" name="trailer" value="<?= htmlspecialchars($movie['trailer_link']) ?>" required>

            <label>New Poster (optional):</label>
            <input type="file" name="poster" accept="image/*">

            <button type="submit">Update Movie</button>
        </form>

        <a href="MovieManage.php" class="back-link">← Back to Manage Movies</a>
    </div>
</body>
</html>
