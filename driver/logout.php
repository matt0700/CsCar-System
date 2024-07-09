<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the index page or login page after logout
header("Location: ../login.php?logout=success");
exit();
?>
