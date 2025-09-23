<?php
session_start();
include 'db_profile.php'; 


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = ''; // Variable to hold messages

// Get user's email for admin replies
$userEmail = '';
if ($stmt = $conn->prepare("SELECT email FROM user WHERE id = ?")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $userEmail = $row['email'];
    }
    $stmt->close();
} else {
    $message = "Database error fetching user email."; // Fallback message
}

// Fetch admin replies
$replies = [];
if ($stmt = $conn->prepare("SELECT subject, reply_message, replied_at FROM enquiry_replies WHERE user_email = ? ORDER BY replied_at DESC LIMIT 3")) {
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }
    $stmt->close();
} else {
    $message = "Database error fetching replies."; 
}

// Edit user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['username'] ?? '');
    $newBio = trim($_POST['bio'] ?? '');
    $profilePicPathToSave = 'image/defaultpfp.jpg'; 

    // Set current profile picture path from database
    $currentProfilePic = 'image/defaultpfp.jpg';
    if ($stmt = $conn->prepare("SELECT profile_picture FROM userprofile WHERE id=?")) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            $currentProfilePic = $data['profile_picture'];
        }
        $stmt->close();
    }
    $profilePicPathToSave = $currentProfilePic; // Assume keeping old pic unless new is uploaded

    // Handle profile picture upload by the user
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension; // Prevent overwrites
        $targetFile = $targetDir . $uniqueFileName;

        // Create 'uploads' folder if it doesn't exist
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0775, true)) { // 0775 for security
                $message = "Failed to create uploads directory. Check server permissions.";
            }
        }

        // Validate image file type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {

            // Move the file that the user uploaded
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                $profilePicPathToSave = $targetFile;

                // Delete the old profile picture if it's not the default and has been changed
                if ($currentProfilePic != 'image/defaultpfp.jpg' && file_exists($currentProfilePic) && $currentProfilePic != $profilePicPathToSave) {
                    unlink($currentProfilePic);
                }
                $message = "Profile updated successfully!";
            } else {
                $message = "Error uploading file. Please try again. (Code: " . $_FILES['profile_picture']['error'] . ")";
                $profilePicPathToSave = $currentProfilePic; // Go back to old path if upload failed
            }
        }
    } else if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $message = "File upload error: " . $_FILES['profile_picture']['error'];
        $profilePicPathToSave = $currentProfilePic; // Go back to old path if an upload error occurred
    }

    // Update userprofile in database
    if ($stmt = $conn->prepare("UPDATE userprofile SET username=?, bio=?, profile_picture=? WHERE id=?")) {
        $stmt->bind_param("sssi", $newUsername, $newBio, $profilePicPathToSave, $userId);
        if (!$stmt->execute()) { // If execute fails
             $message = "Error saving profile: " . $stmt->error;
        } else if (empty($message)) { // If no specific file upload message, show generic success
             $message = "Profile updated successfully!";
        }
        $stmt->close();
    } else {
        $message = "Database error preparing update statement: " . $conn->error;
    }
}

// Check if the user profile exists. if not, create it for new users
if ($stmt = $conn->prepare("SELECT id FROM userprofile WHERE id = ?")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If user profile doesn't exist, insert default values
        if ($insertStmt = $conn->prepare("INSERT INTO userprofile (id, username, bio, profile_picture) VALUES (?, ?, ?, ?)")) {
            $defaultUsername = "User_" . $userId;
            $defaultBio = "Enter your bio here!";
            $defaultProfilePic = 'image/defaultpfp.jpg';
            $insertStmt->bind_param("isss", $userId, $defaultUsername, $defaultBio, $defaultProfilePic);
            $insertStmt->execute();
            $insertStmt->close();
        }
    }
    $stmt->close();
}


// Load the updated user info to display on the page
$username = '';
$bio = '';
$profilePicture = 'image/defaultpfp.jpg';
if ($stmt = $conn->prepare("SELECT username, bio, profile_picture FROM userprofile WHERE id=?")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $username = $user['username'];
        $bio = $user['bio'];
        $profilePicture = $user['profile_picture'];
    }
    $stmt->close();
}


// Fetch favorite movies
$favourites = [];
if ($stmt = $conn->prepare("SELECT m.id, m.title, m.poster FROM user_movie_list uml JOIN movies m ON uml.movie_id = m.id WHERE uml.user_id = ? AND uml.list_type = 'favourite'")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $favourites = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Fetch watchlist movies
$watchlist = [];
if ($stmt = $conn->prepare("SELECT m.id, m.title, m.poster FROM user_movie_list uml JOIN movies m ON uml.movie_id = m.id WHERE uml.user_id = ? AND uml.list_type = 'watchlist'")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $watchlist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="Profile.css">
    <style>
        
        .message {
            padding: 10px;
            margin: 10px auto;
            border-radius: 5px;
            text-align: center;
            max-width: 500px;
            font-weight: bold;
            transition: opacity 0.5s ease-out;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .profile-header {
            margin-top: 50px; 
        }
    </style>
</head>
<body>
<!-- Fixed chat icon and reply panel in the top-right corner -->
<div style="position: fixed; top: 20px; right: 20px; z-index: 999;">
    <div onclick="toggleChat()" style="cursor: pointer;">
        <img src="image/chaticon.png" alt="Chat" style="width: 40px; height: 40px;">
    </div>

    <!-- Show chat box only if there are admin replies -->
    <?php if (!empty($replies)): ?>
    <div id="chatBox" style="display: none; position: absolute; top: 50px; right: 0; width: 320px; background-color: #1a1a1a; color: white; border: 1px solid #888; border-radius: 8px; padding: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.5); max-height: 300px; overflow-y: auto;">
        <strong style="display: block; margin-bottom: 10px;">Admin's Replies:</strong>
        <?php foreach ($replies as $reply): ?>
             <!-- Each reply message with subject and timestamp -->
            <div style="margin-bottom: 15px; border-bottom: 1px solid #444; padding-bottom: 10px;">
                <p style="margin: 0; font-size: 14px;"><strong>About:</strong> <?= htmlspecialchars($reply['subject']) ?></p>
                <p style="margin: 5px 0; font-size: 14px; color:red;"><?= htmlspecialchars($reply['reply_message']) ?></p>
                <p style="font-size: 12px; color: #bbb;"><?= date("M d, Y H:i", strtotime($reply['replied_at'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Main content container -->
<div id="main">
    <!-- Sidebar toggle button using logo -->
    <button class="openbtn" onclick="toggleNav()" style="background: none;">
        <img src="image/Logo.png" alt="logo" style="height: 70px; width: 70px; position: fixed; top: 10px; left: 10px;">
    </button>
</div>

<!-- Profile section header -->
<header class="profile-header">
    <?php if (!empty($message)): ?>
        <div id="profileMessage" class="message <?= strpos($message, 'Error') !== false || strpos($message, 'Failed') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- User profile form -->
    <div class="profile-info">
        <form method="POST" enctype="multipart/form-data" action="profile.php">
            <!-- Profile picture clickable for upload -->
            <label for="profile-pic-input">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Add Profile Picture" class="profile-pic" id="profile-pic" style="cursor:pointer;">
            </label>
            <!-- Hidden file input for profile picture -->
            <input type="file" id="profile-pic-input" name="profile_picture" accept="image/*" style="display: none;">

             <!-- Username and bio input section -->
            <div class="user-details" style="display: flex; flex-direction: column; align-items: center; gap: 10px; margin-top: 15px;">
                <input type="text" name="username" placeholder="Enter username" value="<?= htmlspecialchars($username) ?>"
                    class="username"
                    style="font-size: 24px; text-align: center; padding: 5px 10px; width: 250px; color: white; background-color: transparent;">

                <textarea name="bio" placeholder="Enter your bio here" rows="3" cols="30" class="bio"
                    style="resize: none; text-align: center; padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 300px; color: white; background-color: black;"><?= htmlspecialchars($bio) ?></textarea>

                <!-- Submit button to save profile changes -->
                <button type="submit" style="margin-top: 10px; padding: 8px 16px; font-weight: bold; border: none; background-color: #333; color: white; border-radius: 5px; cursor: pointer;">
                    Save Profile
                </button>
            </div>
        </form>
    </div>
</header>
    <main class="content">

        <!-- Sidebar navigation panel -->
        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">
                <img src="image/Logo.png" alt="logo" style="height: 70px; width: 70px; position: absolute; top: 10px; left: 10px;">
            </a>
            <a href="Home.php">Home</a>
            <a href="Profile.php">Profile</a>
            <a href="Contact.html">Contact Us</a>
            <a href="ManageUserProfile.php">Settings</a>
            <a href="Login.php">Log Out</a>
        </div>

<!-- Favorite movies section -->
<section class="favorites">
    <h2 class="section-title">Favorite Movies</h2>
    <div class="movie-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php foreach ($favourites as $fav): ?>
            <div class="movie-item" style="text-align: center;">
                <a href="Synopsis.php?id=<?= $fav['id'] ?>" style="text-decoration: none; color: inherit;">
                    <img src="<?= htmlspecialchars($fav['poster']) ?>" alt="<?= htmlspecialchars($fav['title']) ?>" style="width: 150px; height: 220px; border-radius: 8px;">
                    <p class="movie-title" style="margin: 10px 0; color: white; font-weight: bold;"><?= htmlspecialchars($fav['title']) ?></p>
                </a>
                <!-- Button to remove movie from favorites -->
                <form action="RemoveFromListProfile.php" method="POST" style="margin-top: 5px;">
                    <input type="hidden" name="movie_id" value="<?= $fav['id'] ?>">
                    <input type="hidden" name="list_type" value="favourite">
                    <button type="submit" style="background-color: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                        Remove
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<br><br><br>

<!-- Watchlist section -->
<section class="watchlist">
    <h2 class="section-title">Watchlist</h2>
    <div class="movie-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php foreach ($watchlist as $watch): ?>
            <div class="movie-item" style="text-align: center;">
                <a href="Synopsis.php?id=<?= $watch['id'] ?>" style="text-decoration: none; color: inherit;">
                    <img src="<?= htmlspecialchars($watch['poster']) ?>" alt="<?= htmlspecialchars($watch['title']) ?>" style="width: 150px; height: 220px; border-radius: 8px;">
                    <p class="movie-title" style="margin: 10px 0; color: white; font-weight: bold;"><?= htmlspecialchars($watch['title']) ?></p>
                </a>
                 <!-- Button to remove movie from watchlist -->
                <form action="RemoveFromListProfile.php" method="POST" style="margin-top: 5px;">
                    <input type="hidden" name="movie_id" value="<?= $watch['id'] ?>">
                    <input type="hidden" name="list_type" value="watchlist">
                    <button type="submit" style="background-color: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                        Remove
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

</main>

<script>
    // Open or close sidebar navigation
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

    // Show or hide the chat box
    function toggleChat() {
        const chat = document.getElementById("chatBox");
        if (chat.style.display === "none" || chat.style.display === "") {
            chat.style.display = "block";
        } else {
            chat.style.display = "none";
        }
    }

    // Update profile picture preview on selection
    document.getElementById('profile-pic-input').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('profile-pic').src = URL.createObjectURL(file);
        }
    });

    // Auto-hide success/error message after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const messageDiv = document.getElementById('profileMessage');
        if (messageDiv) {
            setTimeout(function() {
                messageDiv.style.opacity = '0'; 
                setTimeout(function() {
                    messageDiv.style.display = 'none'; 
                }, 500); 
            }, 3000); 
        }
    });
</script>

<footer>
    <p>&copy; 2024 FeelReel. All Rights Reserved.</p>
</footer>
 
</body> 
</html>