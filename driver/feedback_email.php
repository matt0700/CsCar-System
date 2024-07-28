<?php
require '../vendor/autoload.php';
include 'connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Update trip status script
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trip_id']) && isset($_POST['confirm_end']) && $_POST['confirm_end'] === 'yes') {
    // Update the status of the trip to 'done'
    $trip_id = $_POST['trip_id'];

    $update_sql = "UPDATE trips SET status = 'Done' WHERE trip_id = ?";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param("i", $trip_id);

    if ($update_stmt->execute()) {
        // Send email notification

        // Validate and sanitize inputs if necessary
        if (isset($_POST['email'])) {
            // Retrieve customer email from form
            $customerEmail = $_POST['email'];

            try {
                // Instantiate PHPMailer
                $mail = new PHPMailer(true);

                // Server settings
                $mail->SMTPDebug = 0;                   // Set to 2 for detailed debugging (0 for production)
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';   // SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
                $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password or app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port       = 587;               // TCP port to connect to

                // Recipients
                $mail->setFrom('cscarqc@gmail.com', 'ADMIN');
                $mail->addAddress($customerEmail); // Recipient's email
                $mail->addReplyTo('cscarqc@gmail.com', 'Information');

                // Content
                $mail->isHTML(true); 
                $mail->Subject = 'Trip Complete';
                $mail->Body = '
                <p>Hello,</p>
                
                <p>We appreciate your use of CSCAR! We are pleased to inform you that the driver has successfully completed your trip.</p>
                
                <p>Your feedback is valuable to us! Please take a moment to share your thoughts with us through our <a href="https://docs.google.com/forms/d/e/1FAIpQLSd-dqZ7gmPP0IejLrJsx2rYJJ_p8zByQ6Try9KRgmYLMm-PEQ/viewform?fbclid=IwAR1f6kFfWvIlh9Y7DpCzGLG4IrS6dbWVX-nYeukZ6GNVMB0o6jj8QfM5nBM">Feedback Form</a>.</p>
                
                <p>We look forward to hearing from you!</p>
                
                <p>Thank you for choosing CSCAR!</p>
            ';
                // Send email
                if (!$mail->send()) {
                    throw new Exception('Email sending failed: ' . $mail->ErrorInfo);
                } else {
                    echo "<script>alert('Trip ended successfully and message has been sent to the requester.'); window.history.back();</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Message could not be sent. PHPMailer Error: " . $e->getMessage() . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Requester email not provided.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error updating trip status.'); window.history.back();</script>";
    }

    $update_stmt->close();
    $connect->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
