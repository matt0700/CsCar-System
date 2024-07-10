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

    // Query to fetch driver details from the drivers table based on username and password
    $sql_driver = "SELECT * FROM drivers WHERE username='$username' AND password='$password'";
    $result_driver = mysqli_query($connect, $sql_driver);

    if (mysqli_num_rows($result_driver) === 1) {
        $driver = mysqli_fetch_assoc($result_driver);

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = 'driver';
        $_SESSION['driver_id'] = $driver['driver_id'];
        $_SESSION['driver_name'] = $driver['driver_name'];

        // Redirect to driver dashboard or success message
        header("Location: driver/pages/index.php");
        exit();
    } else {
        // Invalid username or password for driver
        header("Location: driver_login.php?error=Invalid username or password");
        exit();
    }
} else {
    // Redirect if username or password are not set
    header("Location: driver_login.php?error=Username and password are required");
    exit();
}
?>
