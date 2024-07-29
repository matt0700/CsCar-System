<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path as per your project structure

// Include database connection
include "connection.php";

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverId = $_POST['driverId'];
    $email1 = $_POST['email1'];
    $email2 = $_POST['email2'];

    // File upload handling
    $attachments = [];
    if (isset($_FILES['attachment1']) && $_FILES['attachment1']['error'] === UPLOAD_ERR_OK) {
        $attachments[] = [
            'tmp_name' => $_FILES['attachment1']['tmp_name'],
            'name' => $_FILES['attachment1']['name'] // Original file name
        ];
    }
    if (isset($_FILES['attachment2']) && $_FILES['attachment2']['error'] === UPLOAD_ERR_OK) {
        $attachments[] = [
            'tmp_name' => $_FILES['attachment2']['tmp_name'],
            'name' => $_FILES['attachment2']['name'] // Original file name
        ];
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2;                    // Enable verbose debug output
        $mail->isSMTP();                         // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';    // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                // Enable SMTP authentication
        $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
        $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password (or app-specific password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                            // TCP port to connect to

        // Sender and recipient settings
        $mail->setFrom('cscarqc@gmail.com', 'ADMIN');

        // Add recipients
        if (!empty($email1)) {
            $mail->addAddress($email1); // First recipient's email
        }
        if (!empty($email2)) {
            $mail->addAddress($email2); // Second recipient's email
        }

        $mail->addReplyTo('cscarqc@gmail.com', 'Information');

        // Attach files
        foreach ($attachments as $attachment) {
            // Add each attachment with original file name
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        // Content
        $mail->isHTML(true); 
        $mail->Subject = 'RUV Schedule';
        $mail->Body = '
        <p>Hello,</p>
        
        <p>We are delighted to provide you with a copy of your RUV/TRIP TICKET for your records!</p>
        
        <p>Please find the details enclosed in the attached document. If you have any questions or need further assistance, feel free to reach out to us.</p>
        
        <p>Thank you for choosing CSCAR!</p>
        
        <p>Best regards,</p>
        <p>The CSCAR Team</p>
    ';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // Send email
        $mail->send();
        echo 'Email sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Please select an Email";
    }
} else {
    echo 'Invalid request';
}
?>
