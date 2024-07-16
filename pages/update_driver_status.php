<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../connection.php";

    $driver_id = $_POST['driver_id'];
    $status = $_POST['status'];

    // Prepare update statement
    $stmt = $connect->prepare("UPDATE drivers SET driver_status = ? WHERE driver_id = ?");
    $stmt->bind_param("ss", $status, $driver_id);

    if ($stmt->execute()) {
        // Redirect back to the page where the form was submitted
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
    $connect->close();
}
?>
