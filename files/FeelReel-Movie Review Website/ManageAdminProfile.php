<?php
session_start(); 

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    die("Not logged in");
}

// Store admin id from session
$admin_id = $_SESSION["admin_id"];

$mysqli = require __DIR__ . "/testDatabase.php";

// Get current admin profile info
$sql = "SELECT username, email FROM admin WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION["admin_id"]);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// If admin not found in database
if (!$admin) {
    die("Admin not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ManageAdminProfile.css">
    <title>Admin Profile Management</title>
</head>
<body>
    <!-- Sidebar toggle button -->
    <div id="main"> 
        <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
            <img src="image/Logo.png" alt="logo" style="height: 60px; width: 60px; position: fixed; top: 10px; left: 10px;"> 
        </button>
    </div>

    <!-- Sidebar menu -->
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

    <div class="container">
        <img src="image/Logo.png" style="width: 50px; text-align: center;" alt="">
        <h2>Admin Profile</h2>
         <!-- Form to update admin name or password -->
        <form action="UpdateAdminProfile.php" method="post">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($admin['username']) ?>" required>

            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password">

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <!-- Script for sidebar toggle -->
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
