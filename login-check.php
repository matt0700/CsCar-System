<?php
session_start();
include "connection.php"; // Ensure this file includes your database connection details

if (isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    // Query to fetch user details from database (without hashing)
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['username'] = $username;
        $_SESSION['user_ID'] = $user_ID;
        header("Location: verify-special-password.php"); // Redirect to success page or dashboard
        exit();
    } else {
        // User not found or credentials do not match
        header("Location: login.php?error=Invalid username or password");
        exit();
    }
} else {
    // Redirect if username or password are not set
    header("Location: login.php?error=Username and password are required");
    exit();
}
?>
