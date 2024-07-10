<?php
session_start();
include "connection.php"; // Ensure this file includes your database connection details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driver_id = $_POST['driver_id'];
    $driver_status = $_POST['status'];

    // Prepare update statement
    $stmt = $connect->prepare("UPDATE drivers SET driver_status = ? WHERE driver_id = ?");
    $stmt->bind_param("ss", $driver_status, $driver_id);

    if ($stmt->execute()) {
        // Update session variable with new status
        $_SESSION['driver_status'] = $driver_status;

        // Redirect back to the page where the form was submitted
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
    $connect->close();
} else {
    // Redirect if accessed without POST method
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}
?>
