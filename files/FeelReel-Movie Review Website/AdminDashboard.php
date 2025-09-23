<?php

session_start();
if (isset($_SESSION["user_id"])) {
    
    //Include databse connection
    $mysqli = require __DIR__ . "/testDatabase.php";
    
    // Get current user details
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    // Total Users
    $user_result = $mysqli->query("SELECT COUNT(*) AS total_users FROM user");
    $user_count = $user_result->fetch_assoc()["total_users"];

    // Total Movies
    $movie_result = $mysqli->query("SELECT COUNT(*) AS total_movies FROM movies");
    $movie_count = $movie_result->fetch_assoc()["total_movies"];

    // Total Reviews
    $review_result = $mysqli->query("SELECT COUNT(*) AS total_reviews FROM reviews");
    $review_count = $review_result->fetch_assoc()["total_reviews"];

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="AdminDashboard.css">
    <title>Admin Dashboard</title>
</head>
<body>

    <!-- Toggle button to open sidebar -->
    <div id="main"> 
        <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
            <img src="image/Logo.png" alt="logo" style="height: 70px; width: 70px; position: fixed; top: 10px; left: 10px;"> 
        </button>
    </div>

    <!--Sidebar navigation-->
    <div id="mySidebar" class="sidebar"> 
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">
            <img src="image/Logo.png" alt="logo" style="height: 70px; width: 70px; position: absolute; top: 10px; left: 10px;">
        </a> 
        <a href="AdminDashboard.php">Home</a>  
        <a href="MovieManage.php">Movies</a>
        <a href="AdminReviewNew.php">Reviews</a>
        <a href="AdminEnquiries.php">Enquiries</a>
        <a href="ManageAdminProfile.php">Settings</a>
        <a href="AdminLogout.php" target="_blank">Log Out</a>
    </div>

    <!--Admin dashboard-->
    <div class="dashboard">
        <h1>Welcome to the Admin Dashboard</h1>

        <!--Movie manage-->
        <a href="MovieManage.php">
            <button onclick="manageMovies()">Manage Movies</button>
        </a>
        <a href="AdminReviewNew.php">
            <button onclick="manageReviews()">Manage Reviews</button>
        </a>
        <a href="AdminEnquiries.php">
            <button onclick="manageMovies()">Manage Enquiries</button>
        </a>
         
        <!-- Statistics boxes -->
        <div class="stats">
            <h2>Site Statistics</h2>
            <div class="stats-container">
                <div class="stat-box">
                <p>Total Users</p>
                <span id="userCount"><?= $user_count ?></span>
            </div>
            <div class="stat-box">
                <p>Total Movies</p>
                <span id="movieCount"><?= $movie_count ?></span>
            </div>
            <div class="stat-box">
                <p>Total Reviews</p>
                <span id="reviewCount"><?= $review_count ?></span>
            </div>

            </div>
        </div>  
    </div>

     <!-- JavaScript for opening and closing the sidebar -->
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

    <footer>
        &copy; 2024 FeelReel. All rights reserved.
    </footer>
</body>
</html>
