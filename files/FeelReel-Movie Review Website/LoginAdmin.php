<?php

$is_invalid = false; // Flag to track login status

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connect to database
    $mysqli = require __DIR__ . "/testDatabase.php";
    
    // Query for admin using escaped email (prevents SQL injection)
    $sql = sprintf("SELECT * FROM admin
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    // Fetch admin data
    $user = $result->fetch_assoc();

    if ($user) {
        
        // Verify hashed password
        if (password_verify($_POST["password"], $user["password_hash"])) {
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["admin_id"] = $user["id"];
            
            header("Location: AdminDashboard.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Log-in</title> 
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="Login.css"> 
    </head>

    <body>
        
        <div class="wrapper">
            <!-- Login form -->
            <form method="post" > 
                <img src="image/Logo.png">
                <h1>Login</h1>
                    <?php if ($is_invalid): ?>
                        <em>Invalid login</em>
                    <?php endif; ?>
                <div class="input-box">
                    <input type="text" placeholder="Email" name="email" id="email" 
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"> <!-- Keep entered email after submit -->
                </div>
                <div class="input-box">
                    <input type="password" placeholder="Password" name="password" id="password">
                </div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox">Remember me
                    </label>
                    <a href="ForgotPassword.php?type=admin">Forgot password?</a>
                </div>

                <button type="submit" class="btn">Login</button>

                <div class="register-link">
                    <p>Dont have an account? <a href="SignUpAdmin.html" target="_blank">Sign-Up</a></p>
                </div>
            </form>
        </div>
    </body>
</html>