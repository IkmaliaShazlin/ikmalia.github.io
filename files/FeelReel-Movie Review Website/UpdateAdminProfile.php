<?php
session_start();
require 'db_admin.php'; // your database connection (using $conn)

// Make sure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    die("Not logged in. <a href='LoginAdmin.php'>Login here</a>");
}

$admin_id = $_SESSION["admin_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["name"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);

    // Check required
    if (empty($username)) {
        die("Name is required. <a href='ManageAdminProfile.php'>Go back</a>");
    }

    if (!empty($password) || !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            die("Passwords do not match. <a href='ManageAdminProfile.php'>Go back</a>");
        }

        // Password validations (optional)
        if (strlen($password) < 8) {
            die("Password must be at least 8 characters. <a href='ManageAdminProfile.php'>Go back</a>");
        }

        // Update username + password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET username=?, password_hash=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $hashed_password, $admin_id);

    } else {
        // Update username only
        $stmt = $conn->prepare("UPDATE admin SET username=? WHERE id=?");
        $stmt->bind_param("si", $username, $admin_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully! <a href='ManageAdminProfile.php'>Go back</a>";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
