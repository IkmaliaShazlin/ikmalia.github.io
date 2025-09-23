<?php

// Start the session to access session variables
session_start();

// Destroy all session data (logs out the user)
session_destroy();

// Redirect user to the admin front page after logout
header("Location: AdminFrontPage.html");
exit;