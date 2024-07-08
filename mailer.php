<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Instantiation and passing `true` enables exceptions
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
    $mail->setFrom('cscarqc@gmail.com', 'Mailer');
    $mail->addAddress('wetthamcypres2323@gmail.com', 'Matthew Cypres'); 
    $mail->addReplyTo('cscarqc@gmail.com', 'Information');

    // Content
    $mail->isHTML(true); 
    $mail->Subject = 'DURAEMOND POGING BAGSIK';
    $mail->Body    = 'Matthew babaero';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
