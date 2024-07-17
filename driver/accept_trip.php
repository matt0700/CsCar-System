<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trip_id']) && isset($_POST['confirm_accept'])) {
    $trip_id = $_POST['trip_id'];
    $confirm_accept = $_POST['confirm_accept'];

    if ($confirm_accept === 'yes') {
        // Update the status of the trip to 'ongoing'
        $update_sql = "UPDATE trips SET status = 'Ongoing' WHERE trip_id = ?";
        $update_stmt = $connect->prepare($update_sql);
        $update_stmt->bind_param("i", $trip_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<script>alert('Trip Accepted'); window.history.back();</script>";
    } elseif ($confirm_accept === 'no' && isset($_POST['deny_reason'])) {
        $deny_reason = $_POST['deny_reason'];


         // Update the status of the trip to 'denied'
         $update_sql = "UPDATE trips SET status = 'Denied' WHERE trip_id = ?";
         $update_stmt = $connect->prepare($update_sql);
         $update_stmt->bind_param("i", $trip_id);
         $update_stmt->execute();
         $update_stmt->close();
 

        // Process the denial reason (e.g., store in database, send notification, etc.)
        $deny_sql = "INSERT INTO trip_denials (trip_id, deny_reason) VALUES (?, ?)";
        $deny_stmt = $connect->prepare($deny_sql);
        $deny_stmt->bind_param("is", $trip_id, $deny_reason);
        $deny_stmt->execute();
        $deny_stmt->close();

        echo "<script>alert('Trip Denied'); window.history.back();</script>";
    } else {
        echo "<script>alert('Invalid request.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

$connect->close();
?>
