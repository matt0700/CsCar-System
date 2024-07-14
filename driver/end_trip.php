<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trip_id']) && isset($_POST['confirm_end']) && $_POST['confirm_end'] === 'yes') {
    // Update the status of the trip to 'done'
    $update_sql = "UPDATE trips SET status = 'done' WHERE trip_id = ?";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param("i", $_POST['trip_id']);
    $update_stmt->execute();
    $update_stmt->close();

    // Optionally, you can perform other actions after updating the status
    // For example, you may want to send notifications or log the end of the trip
    echo "<script>alert('Trip ended successfully.'); window.history.back();</script>";
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

$connect->close();
?>
