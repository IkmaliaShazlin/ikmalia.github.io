<?php
$host = "localhost";
$dbname = "testdb"; // Replace with your DB
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM enquiries ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Enquiries</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f5f5;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #444;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        textarea {
            font-family: Arial;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        button[type="submit"] {
            margin-top: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        /* Sidebar styles copied from AdminMovieManage.php's linked CSS */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
            font-family: 'Times New Roman';
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border: none;
        }

        .openbtn:hover {
            background-color: #444;
        }

        /* Footer styles - You might want to move this to a shared CSS file later */
        footer {
            text-align: center;
            padding: 1rem;
            background-color: #20232a; /* Example background */
            color: #fff;
            margin-top: 2rem; /* Add some space above the footer */
            position: relative; /* If you want it always at the bottom of the page content */
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="main">
        <button class="openbtn" onclick="toggleNav()" style="background: none;">
            <img src="image/Logo.png" alt="logo" style="height: 60px; width: 60px; position: fixed; top: 10px; left: 10px;">
        </button>
    </div>

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

    <h1>User Enquiries</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Submitted At</th>
            <th>Reply</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["subject"]) ?></td>
            <td><?= nl2br(htmlspecialchars($row["message"])) ?></td>
            <td><?= $row["submitted_at"] ?></td>
            <td>
                <form action="SendReply.php" method="POST">
                    <input type="hidden" name="to_email" value="<?= htmlspecialchars($row['email']) ?>">
                    <input type="hidden" name="subject" value="REPLY: <?= htmlspecialchars($row['subject']) ?>">
                    <input type="hidden" name="enquiry_id" value="<?= $row['id'] ?>">
                    <textarea name="message" rows="2" cols="30" placeholder="Type reply here..." required></textarea>
                    <br>
                    <button type="submit">Send Reply</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>

    <footer>
        © 2024 FeelReel. All rights reserved.
    </footer>

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

<?php
$conn->close();
?>