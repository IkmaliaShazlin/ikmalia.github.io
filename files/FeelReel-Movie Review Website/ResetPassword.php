<?php
session_start();

// Get type from URL (defaults to "user"). Can be "admin" or "user"
$type = $_GET["type"] ?? "user";

// Choose the correct table based on type
$table = $type === "admin" ? "admin" : "user";

// Pick session field name to validate identity
$id_field = $type === "admin" ? "reset_admin_id" : "reset_user_id";

// If no valid session ID is found, block access
if (!isset($_SESSION[$id_field])) {
    die("Unauthorized access.");
}

// Connect to database
$mysqli = require __DIR__ . "/testDatabase.php";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    // Check password length
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } else {
        // Hash the new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $id = $_SESSION[$id_field];

        // Update password in database
        $sql = "UPDATE $table SET password_hash = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $password_hash, $id);

        // If update successful, clear session and redirect
        if ($stmt->execute()) {
            unset($_SESSION[$id_field]);
            $login_page = $type === "admin" ? "LoginAdmin.php" : "Login.php";
            header("Location: $login_page?reset=success");
            exit;
        } else {
            $message = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
<div class="wrapper">
    <form method="post">
        <img src="image/Logo.png">
        <h1>Reset Password</h1>
        <!-- Show error or success message -->
        <?php if ($message): ?>
            <em><?= htmlspecialchars($message) ?></em>
        <?php endif; ?>
        <div class="input-box">
            <input type="password" name="password" placeholder="New password" required>
        </div>
         <!-- Confirm password input -->
        <div class="input-box">
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>
</body>
</html>
