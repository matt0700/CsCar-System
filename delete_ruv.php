<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['ruvNO'])) {
    $ruvNO = $_GET['ruvNO'];

    // Perform database update to mark the RUV request as approved (adjust your SQL query as per your database structure)
    require_once("connection.php"); // Ensure connection is established

    // Update query example (modify according to your schema)
    $updateQuery = "UPDATE ruv_table SET status = 'Denied' WHERE ruvNO = '$ruvNO'";
    $result = mysqli_query($connect, $updateQuery);

    if ($result) {
    // Fetch customer email from database based on $ruvNO
    $getEmailQuery = "SELECT email FROM ruv_table WHERE ruvNO = '$ruvNO'";
    $emailResult = mysqli_query($connect, $getEmailQuery);
    $row = mysqli_fetch_assoc($emailResult);
    $customerEmail = $row['email'];

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

        
    // Server settings
    $mail->SMTPDebug = 2;                    // Enable verbose debug output
    $mail->isSMTP();                         // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';    // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                // Enable SMTP authentication
    $mail->Username   = 'cscarqc@gmail.com'; // SMTP username
    $mail->Password   = 'vshjvtiagxwmbkro'; // SMTP password (or app-specific password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port       = 587;                            // TCP port to connect to

    // Recipients
    $mail->setFrom('cscarqc@gmail.com', 'ADMIN');
    $mail->addAddress($customerEmail); // Recipient's email
    $mail->addReplyTo('cscarqc@gmail.com', 'Information');

    // Content
    $mail->isHTML(true); 
    $mail->Subject = 'RUV Disapproved';
    $mail->Body = '
    <p>Hello,</p>
    <p>We regret to inform you that your request with the RUV number <strong>' . $ruvNO . '</strong> has been disapproved due to Unavailable drivers and vehicles.</p>
    <p>If you have any questions or need further assistance, please do not hesitate to contact us.</p>
    <p>Thank you for your understanding.</p>
    <p>Sincerely,<br>CSCAR</p>
';   


    if ($mail->send()) 
    {
        echo "<script>alert('Message has been sent successfully.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
    mysqli_close($connect);

}else {
    echo 'Failed to update status.';
}
} else {
echo 'Invalid request.';
}
?>