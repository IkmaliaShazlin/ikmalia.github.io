<?php

$is_invalid = false; // Flag to track invalid login

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Connect to DB
    $mysqli = require __DIR__ . "/testDatabase.php";
    
    // Query for the user using escaped email (basic protection against SQL injection)
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc(); // Fetch user data

    if ($user) {

        // Verify hashed password
        if (password_verify($_POST["password"], $user["password_hash"])) {
            session_start(); // Start session
            
            session_regenerate_id(); // Prevent session fixation
            
            $_SESSION["user_id"] = $user["id"]; // Store user ID in session
            
            header("Location: Home.php"); //Redirect after login
            exit;
        }
    }
    
    $is_invalid = true; //Invalid login
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
            <form method="post"> 
                <img src="image/Logo.png">
                <h1>Login</h1>
                    <!-- Show error message if login fails -->
                    <?php if ($is_invalid): ?>
                    <em>Invalid login</em>
                    <?php endif; ?>
                
                <!-- Email Input (Retains value on error) -->
                <div class="input-box">
                    <input type="text" placeholder="Email" name="email" id="email" 
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                </div>
                <!-- Password Input -->
                <div class="input-box">
                    <input type="password" placeholder="Password" name="password" id="password">
                </div>
                
                <!-- "Remember me" (No functionality yet) -->
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox">Remember me
                    </label>
                    <a href="ForgotPassword.php?type=user">Forgot password?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn">Login</button>
                
                <!-- Sign-Up Link -->
                <div class="register-link">
                    <p>Dont have an account? <a href="SignUp.html" target="_blank">Sign-Up</a></p>
                </div>
            </form>
        </div>
    </body>
</html>