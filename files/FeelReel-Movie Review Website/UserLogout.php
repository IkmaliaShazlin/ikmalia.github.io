<?php

// Start or resume the current session
session_start();

// Destroy all session data (logs out the user)
session_destroy();

// Redirect the user to the front page after logging out
header("Location: UserFrontPage.html");
exit;