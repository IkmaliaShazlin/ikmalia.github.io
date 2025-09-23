<?php
require 'testDatabase.php'; // Database connection

// Ensure the request method is POST (to prevent unwanted GET requests)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $action = $_POST['action'];

    // Prepare SQL query based on the action
    if ($action === 'approve') {
        // Approve review: update status to 'approved'
        $sql = "UPDATE reviews SET status = 'approved' WHERE review_id = ?";
    } elseif ($action === 'delete') {
        // Delete review from database
        $sql = "DELETE FROM reviews WHERE review_id = ?";
    } else {
        // Invalid action provided
        die("Invalid action");
    }

    // Execute the prepared statement
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();

    // Redirect back to admin dashboard after action
    header("Location: AdminReviewNew.php");
    exit;
}
?>
