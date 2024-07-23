<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendEmail($toEmails, $messages, $fromEmail = 'cscarqc@gmail.com', $fromName = 'CSCAR') {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;               // Enable SMTP authentication
        $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
        $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password (or app-specific password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;               // TCP port to connect to

        // Sender
        $mail->setFrom($fromEmail, $fromName);

        // Send email to each recipient with their specific message
        foreach ($toEmails as $index => $toEmail) {
            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->clearAddresses(); // Clear previous addresses
                $mail->addAddress($toEmail);
                
                $mail->isHTML(false); // Send as plain text
                $mail->Subject = $messages[$index]['subject'];
                $mail->Body    = $messages[$index]['message'];
                
                $mail->send();
            } else {
                throw new Exception("Invalid email address: $toEmail");
            }
        }

        echo "<script>alert('Trip updated successfully and messages have been sent.'); window.location.href = window.location.href;</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. PHPMailer Error: " . $e->getMessage() . "'); window.location.href = window.location.href;</script>";
    }
}
?>