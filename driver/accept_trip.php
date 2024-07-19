<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Path to autoload.php of PHPMailer

session_start();
include "connection.php";

// Check if user is logged in
if (!isset($_SESSION['driver_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get the logged-in driver's ID
$driverId = $_SESSION['driver_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_accept'])) {
    $tripId = $_POST['trip_id'];
    $confirmAccept = $_POST['confirm_accept'];
    $denyReason = isset($_POST['deny_reason']) ? $_POST['deny_reason'] : '';

    if ($confirmAccept == 'yes') {
        // Update trip status to 'Ongoing'
        $update_sql = "UPDATE trips SET status = 'Ongoing' WHERE trip_id = ?";
        $update_stmt = $connect->prepare($update_sql);
        $update_stmt->bind_param("i", $tripId);
        $update_stmt->execute();
        $update_stmt->close();

        // Get RUV details for email
        $ruvSql = "SELECT * FROM ruv_table WHERE ruvNO = (SELECT ruvNO FROM trips WHERE trip_id = ?)";
        $ruvStmt = $connect->prepare($ruvSql);
        $ruvStmt->bind_param("i", $tripId);
        $ruvStmt->execute();
        $ruvResult = $ruvStmt->get_result();
        $ruvData = $ruvResult->fetch_assoc();
        $ruvStmt->close();

        // Get Driver details
        $driverSql = "SELECT d.driver_name, d.driver_cellno FROM drivers d INNER JOIN trips t ON d.driver_id = t.driver_id WHERE t.trip_id = ?";
        $driverStmt = $connect->prepare($driverSql);
        $driverStmt->bind_param("i", $tripId);
        $driverStmt->execute();
        $driverResult = $driverStmt->get_result();
        $driverData = $driverResult->fetch_assoc();
        $driverStmt->close();

        // Send email to requester
        $toEmail = $ruvData['email']; // Change to the column name where email is stored in your 'ruv_table'
        $subject = "Great News! Your Trip Request Has Been Accepted";
        $message = "Hey there,\n\nWe are excited to inform you that your trip request has been approved! \n\n";
        $message .= "Here are the details of your upcoming trip:\n";
        $message .= "🚗 Pickup Point: " . $ruvData['pickup_point'] . "\n";
        $message .= "📍  Destination: " . $ruvData['destination'] . "\n";
        $message .= "📅 Trip Date: " . $ruvData['trip_date'] . "\n";
        $message .= "⏰ Preferred Time: " . $ruvData['pref_time'] . "\n\n";
        $message .= "Driver Information:\n";
        $message .= "👤 Name: " . $driverData['driver_name'] . "\n";
        $message .= "📞 Contact: " . $driverData['driver_cellno'] . "\n\n";
        $message .= "If you have any questions or need to make changes to your trip, please do not hesitate to contact us.\n\n";
        $message .= "Thank you for choosing our service. We look forward to serving you!\n\nBest regards,\nCSCAR";

        sendEmail($toEmail, $subject, $message);

        // Update status to blank (assuming setting it to an empty value or null)
        $update_ruv_sql = "UPDATE ruv_table SET status = '' WHERE ruvNO = ?";
        $update_ruv_stmt = $connect->prepare($update_ruv_sql);
        $update_ruv_stmt->bind_param("i", $ruvData['ruvNO']);
        $update_ruv_stmt->execute();
        $update_ruv_stmt->close();
    } elseif ($confirmAccept == 'no') {
        // Update trip status to 'Denied' and save reason in trips table
        $update_sql = "UPDATE trips SET status = 'Denied', deny_reason = ? WHERE trip_id = ?";
        $update_stmt = $connect->prepare($update_sql);
        $update_stmt->bind_param("si", $denyReason, $tripId);

        if ($update_stmt->execute()) {
            // Trip status updated successfully
            echo "<script>alert('Trip denied successfully.'); window.history.back();</script>";

            // Send email to requester with deny reason
            $ruvSql = "SELECT * FROM ruv_table WHERE ruvNO = (SELECT ruvNO FROM trips WHERE trip_id = ?)";
            $ruvStmt = $connect->prepare($ruvSql);
            $ruvStmt->bind_param("i", $tripId);
            $ruvStmt->execute();
            $ruvResult = $ruvStmt->get_result();
            $ruvData = $ruvResult->fetch_assoc();
            $ruvStmt->close();

            $toEmail = ('cscarqc@gmail.com'); // Change to the appropriate column name for email in your 'ruv_table'
            $subject = "Important Notice: Trip Request Denied";
            $message = "Hi,\n\nWe regret to inform you that your recent trip request has been denied. Below is the reason provided:\n\n";
            $message .= "$denyReason\n\n";
            $message .= "We apologize for any inconvenience this may cause. If you have any questions or need further assistance, please do not hesitate to contact our support team.\n\n";
            $message .= "Thank you for understanding.\n\nBest regards,\nCSCAR";

            sendEmail($toEmail, $subject, $message); // Assuming sendEmail function is defined elsewhere

            // Update status to blank (assuming setting it to an empty value or null)
            $update_ruv_sql = "UPDATE ruv_table SET status = '' WHERE ruvNO = ?";
            $update_ruv_stmt = $connect->prepare($update_ruv_sql);
            $update_ruv_stmt->bind_param("i", $ruvData['ruvNO']);
            $update_ruv_stmt->execute();
            $update_ruv_stmt->close();

        } else {
            // Error updating trip status
            echo "<script>alert('Error: Unable to deny trip.'); window.history.back();</script>";
        }
    }

    // Redirect or provide feedback as necessary
    echo "<script>window.history.back();</script>";
}

function sendEmail($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';    // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                // Enable SMTP authentication
        $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
        $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password (or app-specific password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                            // TCP port to connect to

        // Recipients
        $mail->setFrom('cscarqc@gmail.com', 'ADMIN');
        $mail->addAddress($toEmail);  // Add a recipient

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "<script>alert('Trip updated successfully and message has been sent.'); window.history.back();</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. PHPMailer Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}

$connect->close();
?>
