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

    // Prepare SQL statement with placeholders
    $sql_driver = "SELECT * FROM drivers WHERE username=? AND password=?";
    $stmt = $connect->prepare($sql_driver);

     // Bind parameters and execute query
     $stmt->bind_param("ss", $username, $password);
     $stmt->execute();
 
     // Get result
     $result_driver = $stmt->get_result();
 
     if ($result_driver->num_rows === 1) {
         $driver = $result_driver->fetch_assoc();

         $_SESSION['driver_id'] = $driver['driver_id'];
        // Redirect to driver dashboard or success message
        header("Location: verify-special-password-driver.php");
        exit();
    } else {
        // User not found or credentials do not match
        header("Location: driverlogin.php?error=Invalid username or password");
        exit();
    }
} else {
    // Redirect if username or password are not set
    header("Location: driverlogin.php?error=Username and password are required");
    exit();
}
?>
