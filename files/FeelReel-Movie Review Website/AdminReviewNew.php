<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Reviews - Movie Recommendations</title>
    <link rel="stylesheet" href="AdminReview.css"> 
</head>
<body>
     <!-- Sidebar navigation for admin dashboard -->
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

    <div id="main">
        <button class="openbtn" onclick="toggleNav()" style="background: none;"> 
            <img src="image/Logo.png" alt="logo" style="height: 60px; width: 60px; position: fixed; top: 10px; left: 10px;"> 
        </button>

        <div class="dashboard">
            <h1 id="main-title">Moderate Reviews</h1>
            <h2>Pending Reviews</h2>
            <ul id="reviews-list">
            <?php
            // Connect to database
            require 'testDatabase.php';

            // Get all reviews with status pending
            $sql = "SELECT r.review_id, r.review_text, r.rating, 
                        u.username AS reviewer_name, 
                        m.title AS movie_title
                    FROM reviews r
                    JOIN user u ON r.user_id = u.id
                    JOIN movies m ON r.movie_id = m.id
                    WHERE r.status = 'pending'";

            $result = $mysqli->query($sql);

            // Display reviews if found
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                <li class="review-item">
                    <strong>Movie:</strong> <?= htmlspecialchars($row['movie_title']) ?><br>
                    <strong>Review:</strong> <?= htmlspecialchars($row['review_text']) ?><br>
                    <strong>Reviewer:</strong> <?= htmlspecialchars($row['reviewer_name']) ?><br>

                    <!-- Form to approve or delete review -->
                    <form method="POST" action="ModerateReviewAction.php">
                        <input type="hidden" name="review_id" value="<?= $row['review_id'] ?>">
                        <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                        <button type="submit" name="action" value="delete" class="delete-btn">Delete</button>
                    </form>

                </li>
            <?php
                endwhile;
            else:
                // If no reviews found
                echo "<li>No pending reviews.</li>";
            endif;
            ?>
            </ul>


            </ul>
        </div>
    </div>
    <br>

    <footer>
        &copy; 2024 FeelReel. All rights reserved.
    </footer>

    <script>
         // Toggle sidebar open and close
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