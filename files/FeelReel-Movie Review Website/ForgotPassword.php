<?php
$show_form = true; // Controls whether the form is shown 
$message = ""; // Stores error/success message

// Determine the type
$type = $_GET["type"] ?? "user"; // default to user

// Set correct table and session ID field based on type (admin or user)
$table = $type === "admin" ? "admin" : "user";
$id_field = $type === "admin" ? "reset_admin_id" : "reset_user_id";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/testDatabase.php";
    $email = trim($_POST["email"]);

    // Query to check if the account exists based on email
    $sql = sprintf("SELECT * FROM %s WHERE email = '%s'",
        $table,
        $mysqli->real_escape_string($email));

    $result = $mysqli->query($sql);
    $account = $result->fetch_assoc();

    if ($account) {
        // If account exists, start session and store user/admin ID
        session_start();
        $_SESSION[$id_field] = $account["id"];

        // Pass type to reset page
        header("Location: ResetPassword.php?type=$type");
        exit;
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
<div class="wrapper">
    <!--Forgot password -->
    <form method="post">
        <img src="image/Logo.png">
        <h1>Forgot Password</h1>
        <?php if ($message): ?>
            <em><?= htmlspecialchars($message) ?></em>
        <?php endif; ?>
        <div class="input-box">
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn">Find Account</button>
    </form>
</div>
</body>
</html>
