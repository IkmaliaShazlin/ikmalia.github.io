<?php
session_start(); // Start session to check user login status

// Check if user is logged in (session contains user_id)
if (!isset($_SESSION["user_id"])) {
    die("Not logged in");
}

$user_id = $_SESSION["user_id"];

$mysqli = require __DIR__ . "/testDatabase.php"; // Connect to database

// Fetch user's username and email from the database
$sql = "SELECT username, email FROM user WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ManageAdminProfile.css"> <!-- reuse same CSS -->
    <title>User Profile Settings</title>
</head>
<body>
    <!-- Sidebar with navigation links -->
    <div id="main"> 
        <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
            <img src="image/Logo.png" alt="logo" style="height: 60px; width: 60px; position: fixed; top: 10px; left: 10px;"> 
        </button>
    </div>

    <div id="mySidebar" class="sidebar"> 
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">
            <img src="image/Logo.png" alt="logo" style="height: 50px; width: 50px; position: absolute; top: 10px; left: 10px;">
        </a> 
        <a href="Home.php">Home</a> 
            <a href="Profile.php">Profile</a> 
            <a href="Contact.html">Contact Us</a>
            <a href="ManageUserProfile.php">Settings</a>
            <a href="Login.php">Log Out</a>
    </div>

    <!-- Profile Update Section -->
    <div class="container">
        <img src="image/Logo.png" style="width: 50px; text-align: center;" alt="">
        <h2>User Profile</h2>
        <form action="UpdateUserProfile.php" method="post">
            <label for="name">Username</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password">

            <button type="submit">Update Profile</button>
        </form>
    </div>

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
    <br>
    <footer>
        &copy; 2024 FeelReel. All rights reserved.
    </footer>
</body>
</html>
