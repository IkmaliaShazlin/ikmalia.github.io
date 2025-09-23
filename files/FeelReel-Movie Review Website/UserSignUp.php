<?php
// Check if the username field is empty
if(empty($_POST["username"])){
    die("Username is required");
}

// Validate the email format
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Check if the password is at least 8 characters long
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

// Check if the password contains at least one letter (case-insensitive)
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

// Check if the password contains at least one number
if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

// Check if password and password confirmation match
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Hash the password securely before storing it in the database
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Connect to the database
$mysqli = require __DIR__ . "/testDatabase.php";

// Prepare the SQL statement to insert the user data
$sql = "INSERT INTO user (username, email, password_hash)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

// Prepare the statement and check for SQL errors
if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Bind the parameters (username, email, password hash) to the SQL statement
$stmt->bind_param("sss",
                    $_POST["username"],
                    $_POST["email"],
                    $password_hash);
    
// Execute the statement and check for success
if ($stmt->execute()) {

    // Redirect to login page after successful registration
    header("Location: Login.php");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        
        // Output any other SQL error
        die($mysqli->error . " " . $mysqli->errno);
    }
}


