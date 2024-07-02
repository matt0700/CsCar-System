<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

// Sanitize username input
$username = mysqli_real_escape_string($connect, $_SESSION['username']);

?>