<?php
require 'vendor/autoload.php'; // Adjust the path as needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['attachment'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['attachment']['name']);

    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
        // File is successfully uploaded, now send the email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 2;                   // Enable verbose debug output
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
            $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password (or app-specific password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('cscarqc@gmail.com', 'ADMIN');
            $mail->addAddress('duraey@gmail.com'); // Recipient's email
            $mail->addReplyTo('cscarqc@gmail.com', 'Information');

            // Attachments
            $mail->addAttachment($uploadFile); // Add the uploaded file

            // Content
            $mail->isHTML(true); 
            $mail->Subject = 'RUV Request Approved';
            $mail->Body    = 'Your RUV request has been approved. Please check your details and schedule.';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Delete the uploaded file after sending the email
        unlink($uploadFile);
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>
