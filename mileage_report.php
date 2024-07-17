<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as necessary
include 'connection.php'; // Assuming your database connection file is named connection.php

// Query to fetch vehicles where current mileage is 5000km more than last check
$query = "SELECT plate_no, model, type, make_series_type, seater, fuel_consump, car_status, mileage, last_mileage_check 
          FROM vehicle_data 
          WHERE mileage >= last_mileage_check + 5000";

$result = mysqli_query($connect, $query);

if ($result) {
    // Check if there are any vehicles meeting the condition
    if (mysqli_num_rows($result) > 0) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 2; // Set to 0 for production
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'cscarqc@gmail.com'; // SMTP username
            $mail->Password = 'vshjvtiagxwmbkro'; // SMTP password or app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('cscarqc@gmail.com', 'ADMIN');
            $mail->addAddress('cscarqc@gmail.com', 'ADMIN');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Alert: Vehicle Mileage Incremented by 5000km';

            // Loop through the results to compose email body
            $emailBody = '';
            while ($row = mysqli_fetch_assoc($result)) {
                $emailBody = 'Vehicle ' . $row['plate_no'] . ' has driven an additional 5000 km. Current mileage: ' . $row['mileage'] . ' km.<br><br>';
                $emailBody .= 'It is recommended to schedule a maintenance check and change the oil soon to keep the vehicle running smoothly.<br><br>';
                $emailBody .= 'Please take necessary action to maintain the vehicle\'s performance and longevity.<br><br>';
                $emailBody .= 'Thank you for your attention to this matter.';
                
                // Update the last mileage check in the database
                $newLastCheck = $row['last_mileage_check'] + 5000;
                $updateQuery = "UPDATE vehicle_data SET last_mileage_check = $newLastCheck WHERE plate_no = '" . $row['plate_no'] . "'";
                mysqli_query($connect, $updateQuery);
            }

            $mail->Body = $emailBody;

            $mail->send();
            echo 'Email notification sent successfully';
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No vehicles have incremented by 5000km since last check.";
    }
} else {
    echo "Error fetching vehicle data: " . mysqli_error($connect);
}

// Close the database connection
mysqli_close($connect);
?>
