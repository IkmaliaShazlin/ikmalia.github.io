<?php

// Validate input: Username must not be empty
if(empty($_POST["username"])){
    die("Username is required");
}

// Validate email format
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Password requirements
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

// Password and confirmation must match
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Securely hash the password before saving and connect to database
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/testDatabase.php";

// Prepare SQL to insert new admin user
$sql = "INSERT INTO admin (username, email, password_hash)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

// If SQL preparation fails
if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Bind user input to the prepared statement
$stmt->bind_param("sss",
                    $_POST["username"],
                    $_POST["email"],
                    $password_hash);
 
// Try to execute the statement
if ($stmt->execute()) {

    header("Location: AdminDashboard.php");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
