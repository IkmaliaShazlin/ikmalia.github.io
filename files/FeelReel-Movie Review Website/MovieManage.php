<?php
include 'db_movie.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']); // Secure the input

    // delete poster file from uploads
    $posterQuery = $conn->prepare("SELECT poster FROM movies WHERE id = ?");
    $posterQuery->bind_param("i", $deleteId);
    $posterQuery->execute();
    $posterResult = $posterQuery->get_result();
    $posterRow = $posterResult->fetch_assoc();
    if ($posterRow && file_exists($posterRow['poster'])) {
        unlink($posterRow['poster']);
    }
    $posterQuery->close(); 

    // Delete movie from DB
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid re-executing deletion on refresh
    header("Location: MovieManage.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $synopsis = $_POST['synopsis'] ?? '';
    $release = $_POST['release'] ?? '';
    $director = $_POST['director'] ?? '';
    $cast = $_POST['cast'] ?? '';
    $language = $_POST['language'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $trailer = $_POST['trailer'] ?? '';

// Convert youtube short links to embed
if (strpos($trailer, 'youtu.be/') !== false) {
    $videoId = substr(parse_url($trailer, PHP_URL_PATH), 1);
    $trailer = "https://www.youtube.com/embed/" . $videoId;
}

// Convert normal YouTube links
$trailer = str_replace("watch?v=", "embed/", $trailer);

    // Handle file upload
    if (!empty($_FILES['poster']['name'])) {
        $fileName = basename($_FILES['poster']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        move_uploaded_file($_FILES['poster']['tmp_name'], $targetFile);
        $posterPath = $targetFile;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO movies (title, synopsis, release_year, director, cast, language, genre, poster, trailer_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssss", $title, $synopsis, $release, $director, $cast, $language, $genre, $posterPath, $trailer);
    $stmt->execute();
    $stmt->close();
}

// Fetch all movies
$result = $conn->query("SELECT * FROM movies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - Movie Recommendations</title>
    <link rel="stylesheet" href="MovieManage.css">
</head>
<style>
    table, th, td {
        border: 1px solid black;
    }
</style>
<body>

    <!-- Sidebar toggle button (logo button to open/close the menu) -->
    <div id="main"> 
        <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
            <img src="image/Logo.png" alt="logo" style="height: 60px; width: 60px; position: fixed; top: 10px; left: 10px;"> 
        </button>
    </div>

    <!-- Sidebar navigation for admin panel -->
    <div id="mySidebar" class="sidebar"> 
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">
            <img src="image/Logo.png" alt="logo" style="height: 50px; width: 50px; position: absolute; top: 10px; left: 10px;">
        </a> 
        <a href="AdminDashboard.php">Home</a> 
        <a href="MovieManage.php">Movies</a>
        <a href="AdminReviewNew.php">Reviews</a>
        <a href="AdminEnquiries.php">Enquiries</a>
        <a href="ManageAdminProfile.php">Settings</a>
        <a href="AdminLogout.php">Log Out</a>
    </div>

    <h1>Manage Movies</h1>
<!-- Form for admin to add a new movie -->
<form action="#" method="POST" enctype="multipart/form-data">
    <h2>Add a New Movie</h2>
    
    <!-- Basic movie details input -->
    <label for="title">Movie Title:</label>
    <input type="text" id="title" name="title" required><br><br>
    
    <label for="synopsis">Synopsis:</label>
    <textarea id="synopsis" name="synopsis" required></textarea><br><br>
    
    <label for="release">Release Year:</label>
    <input type="number" id="release" name="release" required><br><br>
    
    <label for="director">Director:</label>
    <input type="text" id="director" name="director" required><br><br>
    
    <label for="cast">Main Cast:</label>
    <input type="text" id="cast" name="cast" required><br><br>

    <label for="language">Language:</label>
    <input type="text" id="language" name="language" required><br><br>

    <label for="genre">Genre:</label>
    <input type="text" id="genre" name="genre" required><br><br>

    
    <label for="poster">Movie Poster:</label>
    <input type="file" id="poster" name="poster" accept="image/*" required><br><br>
    
    <label for="trailer">Trailer Link:</label>
    <input type="url" id="trailer" name="trailer" required><br><br>
    
    <button type="submit">Add Movie</button>
</form>

    <h2>Existing Movies</h2>

    <!-- List of existing movies for Edit/Delete options -->
    <table>
            <tr>
                <th>Title</th>
                <th>Synopsis</th>
                <th>Release Year</th>
                <th>Actions</th>
            </tr>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['synopsis']) ?></td>
            <td><?= htmlspecialchars($row['release_year']) ?></td>
            <!-- Edit and Delete buttons for each movie -->
            <td>
                <a href="EditMovie.php?id=<?= $row['id'] ?>">
                    <button>Edit</button>
                </a>
                <a href="MovieManage.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this movie?');">
                    <button>Delete</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    </table>
    <br>

    <footer>
        &copy; 2024 FeelReel. All rights reserved.
    </footer>

    <!-- Sidebar toggle function -->
    <script>
        function toggleNav() {
            let sidebar = document.getElementById("mySidebar");
            let main = document.getElementById("main");
            if (sidebar.style.width === "250px") {
                sidebar.style.width = "0";
                main.style.marginLeft = "0";
            } else {
                sidebar.style.width = "250px";
                main.style.marginLeft = "250px";
            }
        }
    </script>

</body>
</html>