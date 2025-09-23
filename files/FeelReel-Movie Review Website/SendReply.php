<?php
$host = "localhost";
$dbname = "testdb";
$username = "root";
$password = "";


$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$to_email = $_POST['to_email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$enquiry_id = $_POST['enquiry_id'];

// Save the reply
$stmt = $conn->prepare("INSERT INTO enquiry_replies (user_email, subject, reply_message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $to_email, $subject, $message);

// Execute insert first
$replySuccess = $stmt->execute();
$stmt->close();

// If reply saved successfully, delete the original enquiry
if ($replySuccess) {
    $deleteStmt = $conn->prepare("DELETE FROM enquiries WHERE id = ?");
    $deleteStmt->bind_param("i", $enquiry_id);
    $deleteStmt->execute();
    $deleteStmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reply Status</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f5f5;
            padding: 40px;
            text-align: center;
        }
        .status-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="status-box">
     <?php
    if ($replySuccess) {
        echo "<h2 class='success'>Reply sent and saved successfully!</h2>";
    } else {
        echo "<h2 class='error'>Error: Failed to send or save reply.</h2>";
    }

    $conn->close();
    ?>
    <a href="AdminEnquiries.php">Back to Enquiries</a>
</div>

</body>
</html>
