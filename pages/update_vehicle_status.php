<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../connection.php";

    $plate_no = $_POST['plate_no'];
    $status = $_POST['status'];

    // Prepare update statement
    $stmt = $connect->prepare("UPDATE vehicle_data SET car_status = ? WHERE plate_no = ?");
    $stmt->bind_param("ss", $status, $plate_no);

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
