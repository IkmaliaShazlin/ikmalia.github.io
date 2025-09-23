<?php
session_start();
require 'testDatabase.php'; // adjust to your DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from form safely
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $subject = trim($_POST["subject"] ?? '');
    $message = trim($_POST["message"] ?? '');
    $origin = $_POST["origin"] ?? "Contact.html";  // fallback if not set

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("All fields are required.");
    }

    // Insert enquiry into DB
    $stmt = $mysqli->prepare("INSERT INTO enquiries (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // Success: redirect to confirmation page with origin
        header("Location: EnquirySubmitted.html?origin=" . urlencode($origin));
        exit;
    } else {
        die("Failed to submit enquiry. Please try again.");
    }
} else {
    die("Invalid request.");
}
?>
