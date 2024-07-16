<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validate and sanitize inputs if necessary
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customerEmail'])) {
    // Retrieve customer email from form
    $customerEmail = $_POST['customerEmail'];

    try {
        // Instantiate PHPMailer
        $mail = new PHPMailer(true);

        // Server settings
        $mail->SMTPDebug = 0;                   // Set to 2 for detailed debugging (0 for production)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';   // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@gmail.com'; // Your Gmail username
        $mail->Password   = 'your_password';   // Your Gmail password or app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;               // TCP port to connect to

        // Sender and recipient
        $mail->setFrom('your_email@gmail.com', 'ADMIN');
        $mail->addAddress($customerEmail);     // Recipient's email
        $mail->addReplyTo('your_email@gmail.com', 'Information');

        // Content
        $mail->isHTML(true); 
        $mail->Subject = 'Trip Complete';
        $mail->Body = 'The Driver has completed your trip. If you have any feedback please access this link <a href="https://docs.google.com/forms/d/e/1FAIpQLSd-dqZ7gmPP0IejLrJsx2rYJJ_p8zByQ6Try9KRgmYLMm-PEQ/viewform?fbclid=IwAR1f6kFfWvIlh9Y7DpCzGLG4IrS6dbWVX-nYeukZ6GNVMB0o6jj8QfM5nBM">Feedback Form</a>. Thank you!';
        $mail->AltBody = 'The Driver has completed your trip. If you have any feedback please access this link: https://docs.google.com/forms/d/e/1FAIpQLSd-dqZ7gmPP0IejLrJsx2rYJJ_p8zByQ6Try9KRgmYLMm-PEQ/viewform?fbclid=IwAR1f6kFfWvIlh9Y7DpCzGLG4IrS6dbWVX-nYeukZ6GNVMB0o6jj8QfM5nBM';

        // Send email
        $mail->send();
        echo "<script>alert('Message has been sent successfully.'); window.history.back();</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
} else {
    echo 'Invalid request.';
}
?>
